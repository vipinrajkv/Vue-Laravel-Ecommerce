<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

final class ProductController extends Controller
{
    public function __construct(
        protected readonly ProductService $productService
    )
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  $this->productService->getProducts();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $product)
    {
        $productData = $product->validated();
        $this->productService->store($productData);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id) : JsonResponse
    {
        return  $this->productService->getProduct($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product) : JsonResponse
    {
        return  $this->productService->update($request, $product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        return  $this->productService->update($request, $product);
    }
}
