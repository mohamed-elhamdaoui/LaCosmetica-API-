<?php

namespace App\DAO;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductDAO
{
    public function create(array $data, array $images)
    {
        
        $product = Product::create($data);

        foreach ($images as $image) {
            $path = $image->store('products', 'public');
            $product->images()->create(['path' => $path]);
        }

        return $product->load('images');
    }
}
