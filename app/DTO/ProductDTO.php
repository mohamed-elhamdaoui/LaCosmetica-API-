<?php

namespace App\DTO;

use Illuminate\Http\Request;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $categoryId,
        public readonly array $images
    ) {}

    public static function fromRequest(Request $request): self
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        return new self(
            $validated['name'],
            $validated['description'],
            $validated['price'],
            $validated['stock'],
            $validated['category_id'],
            $request->file('images')
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->categoryId,
        ];
    }
}
