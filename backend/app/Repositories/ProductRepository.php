<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected readonly Product $product
    )
    {

    }

    public function getProductList()
    {
        return $this->product->select('*')
        ->without('created_at', 'updated_at')
        ->limit(4)
        ->get();
    }
    
    public function getProduct(int $id)
    {
        return $this->product->find($id);
    }
    
    public function create(array $product)
    {
        return $this->product->create($product);
    }

}
