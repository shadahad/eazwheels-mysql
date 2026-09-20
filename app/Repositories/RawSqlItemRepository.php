<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\ItemDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class RawSqlItemRepository implements ItemRepositoryInterface
{
    private const CACHE_PREFIX = 'eazwheels:item:';
    private const CACHE_LIST = 'eazwheels:items:all';

    public function create(array $data): ItemDTO
    {
        $id = (string) Str::uuid();
        $now = Carbon::now('Europe/London')->format('Y-m-d H:i:s');

        // Insert using raw SQL with PDO bindings (no ORM)
        DB::insert(
            "INSERT INTO items (id, item_name, item_image, cost, size, units_in_stock, description, created, modified)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $id,
                $data['itemName'],
                $data['itemImage'],
                $data['cost'],
                $data['size'],
                $data['unitsInStock'],
                $data['description'],
                $now,
                $now // modified is set at time of insertion
            ]
        );

        $dto = $this->findById($id);

        if (!$dto) {
            throw new \RuntimeException("Failed to fetch newly created entity with ID: {$id}");
        }

        // Invalidate Redis listings
        Redis::del(self::CACHE_LIST);

        return $dto;
    }

    public function findById(string $id): ?ItemDTO
    {
        $cacheKey = self::CACHE_PREFIX . $id;
        $cached = Redis::get($cacheKey);

        if ($cached) {
            $data = json_decode($cached, false);
            return ItemDTO::fromDatabase($data);
        }

        $records = DB::select("SELECT * FROM items WHERE id = ? LIMIT 1", [$id]);

        if (empty($records)) {
            return null;
        }

        $dto = ItemDTO::fromDatabase($records[0]);
        Redis::setex($cacheKey, 3600, json_encode($dto->toArray()));

        return $dto;
    }

    public function getAll(int $limit = 50, int $offset = 0): array
    {
        $records = DB::select(
            "SELECT * FROM items ORDER BY created DESC LIMIT ? OFFSET ?",
            [(int) $limit, (int) $offset]
        );

        return array_map(fn($row) => ItemDTO::fromDatabase($row), $records);
    }

    public function search(string $keyword): array
    {
        $term = "%{$keyword}%";
        $records = DB::select(
            "SELECT * FROM items 
             WHERE item_name LIKE ? OR description LIKE ? 
             ORDER BY created DESC",
            [$term, $term]
        );

        return array_map(fn($row) => ItemDTO::fromDatabase($row), $records);
    }

    public function updateStock(string $id, int $unitsInStock): bool
    {
        $now = Carbon::now('Europe/London')->format('Y-m-d H:i:s');

        $affected = DB::update(
            "UPDATE items SET units_in_stock = ?, modified = ? WHERE id = ?",
            [$unitsInStock, $now, $id]
        );

        if ($affected > 0) {
            Redis::del(self::CACHE_PREFIX . $id);
            Redis::del(self::CACHE_LIST);
            return true;
        }

        return false;
    }
}