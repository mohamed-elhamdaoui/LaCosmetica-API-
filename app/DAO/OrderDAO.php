<?php
namespace App\DAO;

use App\Models\Order;
use App\Models\Product;

class OrderDAO
{
    public function saveOrder(array $data)
    {
        return Order::create($data);
    }

    public function attachItem(Order $order, array $itemData)
    {
        return $order->items()->create($itemData);
    }

    public function updateStock(Product $product, int $quantity)
    {
        return $product->decrement('stock', $quantity);
    }
}
