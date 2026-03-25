<?php

namespace App\DTO;

use Illuminate\Http\Request;

class ProductUpdateDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stock = null,
        public readonly ?int $categoryId = null,
        public readonly ?array $images = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'images' => 'sometimes|array|min:1|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        return new self(
            $validated['name'] ?? null,
            $validated['description'] ?? null,
            isset($validated['price']) ? (float)$validated['price'] : null,
            isset($validated['stock']) ? (int)$validated['stock'] : null,
            isset($validated['category_id']) ? (int)$validated['category_id'] : null,
            $request->file('images')
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->categoryId,
        ], fn($value) => !is_null($value));
    }
}
