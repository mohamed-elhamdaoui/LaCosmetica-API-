<?php

namespace App\DAO;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function getAll()
    {
        return Product::with('images')->get();
    }


    public function findBySlug(string $slug)
    {
        return Product::with(['images', 'category'])
            ->where('slug', $slug)
            ->first();
    }

    public function findById($id)
    {
        return Product::find($id);
    }

    public function delete(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        return $product->delete();
    }


    public function update(Product $product, array $data, ?array $newImages = null)
    {
        $product->update($data);


        if (!empty($newImages)) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->path);
            }
            $product->images()->delete();

            foreach ($newImages as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return $product->load('images');
    }
}
