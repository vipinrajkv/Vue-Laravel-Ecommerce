<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Repositories\ProductRepository;

use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

final class ProductService
{

    /**
     * Create a new Product Service instance.
     */
    public function __construct(
        protected readonly ProductRepository $productRepository,
        protected readonly ApiResponse $apiResponse,
    ) {
        //
    }

    public function getProducts()
    {
        $product = $this->productRepository->getProductList();

        if ($product->isEmpty()) {
            return $this->apiResponse->sendError('No records found');
        }

        return $this->apiResponse->sendResponse(ProductResource::collection($product), 'Products retrieved successfully.');
    }

    public function getProduct(int $id)
    {
        $product = $this->productRepository->getProduct($id);

        if (is_null($product)){
            return $this->apiResponse->sendError('No records found');
        }

        return $this->apiResponse->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function store(array $product) : JsonResponse
    {
        $productData = [];

        if (isset($product['product_image'])) {
            $imgFile = $product['product_image'];
          
            if ($imgFile->isValid()) {
                $productData['product_image'] =  $this->storeImage($imgFile);
            }
        }

        try {         
                $productData['product_name'] = $product['product_name'];
                $productData['price'] = $product['product_price'];
                $productData['description'] = $product['product_details'];
            
            $product = $this->productRepository->create($productData);
        } catch (\Exception $e) {
            return $this->apiResponse->sendError('Product creation failed', [$e->getMessage()], 500);
        }

        return $this->apiResponse->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    public function update($request, $product) : JsonResponse
    {
        $productData = $request->validated();
        $previousProductImage = $product->product_image;
        
        if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {

            if (!empty($previousProductImage) && file_exists(public_path('/images/products/'. $previousProductImage))){
                unlink(public_path('/images/products/'. $previousProductImage));
            }
            $imgFile = $request->file('product_image');
            $productData['product_image'] = $this->storeImage($imgFile);
        } 

        try {
             $product->update($productData);
        } catch (\Exception $e) {
            return $this->apiResponse->sendError('Product creation failed',[$e->getMessage()], 500);
        } 

        return $this->apiResponse->sendResponse(new ProductResource($product), 'Product Updated successfully.'); 
    }

    public function destroy($request, $product) : JsonResponse
    {
        $filePath = public_path('images/products/' . $product->product_image);

        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                return $this->apiResponse->sendError("Failed to delete the product image.");
            }
        }

        if ($product->delete()) {
            return $this->apiResponse->sendResponse(new ProductResource($product), 'Product Deleted successfully.');
        }

        return $this->apiResponse->sendError("Product Can't be deleted.");
    }



    /**
     * Store the uploaded image and return the image file name
     *
     * @param \Illuminate\Http\UploadedFile $imgFile
     * @return string
     */
    private function storeImage($imgFile): string
    {
        $productImage = time() . '.' . $imgFile->getClientOriginalExtension();
        $destinationPath = public_path('/images/products');
        $imgFile->move($destinationPath, $productImage);

        return $productImage;
    }
}
