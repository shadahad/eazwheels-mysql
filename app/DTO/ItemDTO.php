<?php

declare(strict_types=1);

namespace App\DTO;

readonly class ItemDTO
{
    public function __construct(
        public string $id,
        public string $itemName,
        public string $itemImage,
        public float $cost,
        public float $size,
        public int $unitsInStock,
        public string $description,
        public string $created,
        public ?string $modified = null
    ) {}

    public static function fromDatabase(object|array $row): self
    {
        $data = (array) $row;

        return new self(
            id: (string) ($data['id'] ?? ''),
            itemName: (string) ($data['item_name'] ?? $data['itemName'] ?? ''),
            itemImage: (string) ($data['item_image'] ?? $data['itemImage'] ?? ''),
            cost: (float) ($data['cost'] ?? 0.0),
            size: (float) ($data['size'] ?? 0.0),
            unitsInStock: (int) ($data['units_in_stock'] ?? $data['unitsInStock'] ?? 0),
            description: (string) ($data['description'] ?? ''),
            created: (string) ($data['created'] ?? ''),
            modified: isset($data['modified']) && $data['modified'] !== null ? (string) $data['modified'] : null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'itemName' => $this->itemName,
            'itemImage' => $this->itemImage,
            'cost' => $this->cost,
            'size' => $this->size,
            'unitsInStock' => $this->unitsInStock,
            'description' => $this->description,
            'created' => $this->created,
            'modified' => $this->modified,
        ];
    }
}