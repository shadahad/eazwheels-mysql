<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    private const ADMIN_KEY = 'eaz_sec_9f82d1c7a4b04e6e8d1234567890abcdef';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('app.admin_api_key', self::ADMIN_KEY);

        // Ensure database table exists for integration testing
        DB::statement("
            CREATE TABLE IF NOT EXISTS items (
                id CHAR(36) NOT NULL,
                item_name VARCHAR(255) NOT NULL,
                item_image VARCHAR(1024) NOT NULL,
                cost DECIMAL(10, 2) NOT NULL,
                size DECIMAL(5, 2) NOT NULL,
                units_in_stock INT UNSIGNED NOT NULL DEFAULT 0,
                description TEXT NOT NULL,
                created DATETIME NOT NULL,
                modified DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id),
                CONSTRAINT chk_items_cost_positive CHECK (cost >= 0.00),
                CONSTRAINT chk_items_size_positive CHECK (size > 0.00),
                CONSTRAINT chk_items_stock_non_negative CHECK (units_in_stock >= 0)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        DB::table('items')->truncate();

        try {
            Redis::flushall();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Redis server is not running on 127.0.0.1:6379. Please start Redis.');
        }
    }

    public function test_1_it_rejects_unauthorized_post_request_without_admin_key(): void
    {
        $payload = [
            'itemName' => 'Vortex 15-inch Rim Trims',
            'itemImage' => 'https://example.com/item.jpg',
            'cost' => 34.99,
            'size' => 15.00,
            'unitsInStock' => 20,
            'description' => 'Tested ABS construction with steel rings.'
        ];

        $response = $this->postJson('/api/items', $payload);

        $response->assertStatus(401)
                 ->assertJson(['success' => false]);
    }

    public function test_2_it_creates_an_item_successfully_when_valid_input_is_provided(): void
    {
        $payload = [
            'itemName' => 'AeroShield 16 Spoilers',
            'itemImage' => 'https://example.com/spoiler.jpg',
            'cost' => 59.99,
            'size' => 16.00,
            'unitsInStock' => 12,
            'description' => 'Impact resistant virgin ABS aerodynamic spoiler with pre-applied adhesive.'
        ];

        $response = $this->withHeaders(['X-Admin-Key' => self::ADMIN_KEY])
                         ->postJson('/api/items', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonPath('data.itemName', 'AeroShield 16 Spoilers')
                 ->assertJsonPath('data.cost', 59.99);

        // Verify size numerically without JSON int/float strict identity mismatch
        $this->assertEquals(16.0, (float) $response->json('data.size'));

        $id = $response->json('data.id');
        $this->assertNotEmpty($id);

        // Verify direct raw SQL persistence
        $persisted = DB::select("SELECT * FROM items WHERE id = ?", [$id]);
        $this->assertCount(1, $persisted);
        $this->assertEquals(59.99, (float) $persisted[0]->cost);
        $this->assertNotNull($persisted[0]->created);
        $this->assertNotNull($persisted[0]->modified);
    }

    public function test_3_it_cleanly_rejects_nonsensical_or_corrupt_inputs_without_persisting(): void
    {
        $payloads = [
            'negative_cost' => [
                'itemName' => 'Curb Trim',
                'itemImage' => 'https://example.com/trim.jpg',
                'cost' => -19.99,
                'size' => 15.00,
                'unitsInStock' => 10,
                'description' => 'Valid description here.'
            ],
            'invalid_size' => [
                'itemName' => 'Curb Trim',
                'itemImage' => 'https://example.com/trim.jpg',
                'cost' => 19.99,
                'size' => -4.00,
                'unitsInStock' => 10,
                'description' => 'Valid description here.'
            ],
            'malformed_url' => [
                'itemName' => 'Curb Trim',
                'itemImage' => 'not-a-valid-url-format',
                'cost' => 19.99,
                'size' => 15.00,
                'unitsInStock' => 10,
                'description' => 'Valid description here.'
            ],
            'negative_stock' => [
                'itemName' => 'Curb Trim',
                'itemImage' => 'https://example.com/trim.jpg',
                'cost' => 19.99,
                'size' => 15.00,
                'unitsInStock' => -5,
                'description' => 'Valid description here.'
            ]
        ];

        foreach ($payloads as $scenario => $payload) {
            $response = $this->withHeaders(['X-Admin-Key' => self::ADMIN_KEY])
                             ->postJson('/api/items', $payload);

            $response->assertStatus(422)
                     ->assertJsonStructure(['status', 'errors']);
        }

        $count = DB::select("SELECT COUNT(*) as cnt FROM items")[0]->cnt;
        $this->assertEquals(0, $count);
    }

    public function test_4_mysql_check_constraints_prevent_bypassing_corruption(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::insert(
            "INSERT INTO items (id, item_name, item_image, cost, size, units_in_stock, description, created, modified)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                '11111111-1111-1111-1111-111111111111',
                'Corrupted DB Bypass',
                'https://example.com/bypass.jpg',
                -99.00,
                14.00,
                5,
                'Corrupted state attempt'
            ]
        );
    }

    public function test_5_redis_caching_and_invalidation_behavior(): void
    {
        $payload = [
            'itemName' => 'SpeedWheel Silver 17',
            'itemImage' => 'https://example.com/silver.jpg',
            'cost' => 45.00,
            'size' => 17.00,
            'unitsInStock' => 8,
            'description' => 'Silver high-impact poly-blend wheel caps.'
        ];

        $res = $this->withHeaders(['X-Admin-Key' => self::ADMIN_KEY])
                    ->postJson('/api/items', $payload);

        $id = $res->json('data.id');

        $repo = app(\App\Repositories\ItemRepositoryInterface::class);
        $itemFirst = $repo->findById($id);

        $this->assertTrue(Redis::exists('eazwheels:item:' . $id) > 0);

        $repo->updateStock($id, 50);

        $this->assertEquals(0, Redis::exists('eazwheels:item:' . $id));

        $itemSecond = $repo->findById($id);
        $this->assertEquals(50, $itemSecond->unitsInStock);
    }
}