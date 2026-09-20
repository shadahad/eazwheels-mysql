<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\ItemDTO;

interface ItemRepositoryInterface
{
    public function create(array $data): ItemDTO;
    public function findById(string $id): ?ItemDTO;
    public function getAll(int $limit = 50, int $offset = 0): array;
    public function search(string $keyword): array;
    public function updateStock(string $id, int $unitsInStock): bool;
}