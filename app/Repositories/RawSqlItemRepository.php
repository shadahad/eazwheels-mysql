<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\ItemDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
                $now
            ]
        );

        $dto = $this->findById($id);

        if (!$dto) {
            throw new \RuntimeException("Failed to fetch newly created entity with ID: {$id}");
        }

        // Invalidate cache
        Cache::forget(self::CACHE_LIST);

        return $dto;
    }

    public function findById(string $id): ?ItemDTO
    {
        $cacheKey = self::CACHE_PREFIX . $id;

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            $data = is_string($cached) ? json_decode($cached, false) : (object) $cached;
            return ItemDTO::fromDatabase($data);
        }

        $records = DB::select("SELECT * FROM items WHERE id = ? LIMIT 1", [$id]);

        if (empty($records)) {
            return null;
        }

        $dto = ItemDTO::fromDatabase($records[0]);
        Cache::put($cacheKey, json_encode($dto->toArray()), 3600);

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

    public function update(string $id, array $data): bool
    {
        $now = Carbon::now('Europe/London')->format('Y-m-d H:i:s');

        $affected = DB::update(
            "UPDATE items 
             SET item_name = ?, item_image = ?, cost = ?, size = ?, units_in_stock = ?, description = ?, modified = ? 
             WHERE id = ?",
            [
                $data['itemName'],
                $data['itemImage'],
                $data['cost'],
                $data['size'],
                (int) $data['unitsInStock'],
                $data['description'],
                $now,
                $id
            ]
        );

        // Invalidate cache keys
        Cache::forget(self::CACHE_PREFIX . $id);
        Cache::forget(self::CACHE_LIST);

        return $affected > 0;
    }

    public function updateStock(string $id, int $unitsInStock): bool
    {
        $now = Carbon::now('Europe/London')->format('Y-m-d H:i:s');

        $affected = DB::update(
            "UPDATE items SET units_in_stock = ?, modified = ? WHERE id = ?",
            [$unitsInStock, $now, $id]
        );

        if ($affected > 0) {
            Cache::forget(self::CACHE_PREFIX . $id);
            Cache::forget(self::CACHE_LIST);
            return true;
        }

        return false;
    }
}