<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Repositories\ProductRepository;

use App\Http\Resources\ProductResource;

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

        if (!$product) {
            return $this->apiResponse->sendError('No records found');
        }

        return $this->apiResponse->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function store($product)
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
