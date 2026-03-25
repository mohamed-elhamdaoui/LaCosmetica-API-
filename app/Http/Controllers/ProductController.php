<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DAO\ProductDAO;
use App\DTO\ProductDTO;

class ProductController extends Controller
{
    protected $productDAO;

    public function __construct(ProductDAO $productDAO)
    {
        $this->productDAO = $productDAO;
    }

    public function store(Request $request)
    {
        $dto = ProductDTO::fromRequest($request);

        $product = $this->productDAO->create($dto->toArray(), $dto->images);

        return response()->json([
            'message' => 'Product added to La Cosmetica!',
            'data' => $product
        ], 201);
    }

    public function index()
    {
        $products = $this->productDAO->getAll();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    public function show($slug)
    {
        $product = $this->productDAO->findBySlug($slug);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce produit n\'existe pas dans notre pharmacie!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }
}
