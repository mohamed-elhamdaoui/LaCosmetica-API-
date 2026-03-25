<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DAO\ProductDAO;
use App\DTO\ProductDTO;
use App\DTO\ProductUpdateDTO;

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


    public function destroy($id)
    {
        $product = $this->productDAO->findById($id);

        if (!$product) {
            return response()->json(['message' => 'Produit non trouvé!'], 404);
        }

        $this->productDAO->delete($product);

        return response()->json([
            'message' => 'Produit et images supprimés définitivement!'
        ]);
    }

    public function update(Request $request, $id)
{
    $product = $this->productDAO->findById($id);
    if (!$product) return response()->json(['message' => 'Produit non trouvé!'], 404);

    $dto = ProductUpdateDTO::fromRequest($request);


    $updatedProduct = $this->productDAO->update($product, $dto->toArray(), $dto->images);

    return response()->json([
        'message' => 'Produit mis à jour avec succès!',
        'data' => $updatedProduct
    ]);
}
}
