<?php
namespace App\Services;

use App\DAO\OrderDAO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderDAO;

    public function __construct(OrderDAO $orderDAO)
    {
        $this->orderDAO = $orderDAO;
    }

    public function placeOrder($userId, array $items)
    {
        return DB::transaction(function () use ($userId, $items) {
            $totalPrice = 0;

            $order = $this->orderDAO->saveOrder([
                'user_id' => $userId,
                'total_price' => 0,
                'status' => 'pending'
            ]);

            foreach ($items as $item) {
                $product = Product::where('slug', $item['slug'])->firstOrFail();

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour " . $product->name);
                }

                $itemPrice = $product->price * $item['quantity'];
                $totalPrice += $itemPrice;

                $this->orderDAO->attachItem($order, [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);

                $this->orderDAO->updateStock($product, $item['quantity']);
            }


            $order->update(['total_price' => $totalPrice]);

            return $order->load('items.product');
        });
    }
}
