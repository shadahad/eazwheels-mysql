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

    public static function fromDatabase(object $row): self
    {
        return new self(
            id: (string) $row->id,
            itemName: (string) $row->item_name,
            itemImage: (string) $row->item_image,
            cost: (float) $row->cost,
            size: (float) $row->size,
            unitsInStock: (int) $row->units_in_stock,
            description: (string) $row->description,
            created: (string) $row->created,
            modified: $row->modified ? (string) $row->modified : null
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