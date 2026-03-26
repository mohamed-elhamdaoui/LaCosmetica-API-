<?php

namespace App\DTO;

use Illuminate\Http\Request;

class OrderDTO
{
    public function __construct(
        public readonly array $items 
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.slug' => 'required|string|exists:products,slug',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        return new self($validated['items']);
    }
}
