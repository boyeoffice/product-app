<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct($data)
    {
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setQuantity($data['quantity']);

        $this->productRepository->save($product);

        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'quantity' => $product->getQuantity()
        ];
    }
}