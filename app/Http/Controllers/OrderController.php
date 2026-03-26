<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\DTO\OrderDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request)
    {
        $dto = OrderDTO::fromRequest($request);

        $order = $this->orderService->placeOrder(Auth::id(), $dto->items);

        return response()->json([
            'message' => 'Commande validée avec succès!',
            'data' => $order
        ], 201);
    }
}
