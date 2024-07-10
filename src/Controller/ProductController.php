<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    #[Route('/products', name: 'product_store', methods: 'POST')]
    public function store(EntityManagerInterface $entityManager): JsonResponse
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setQuantity(2);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->json([
            'message' => 'Product created successfully.',
            'data' => $product
        ],201);
    }
}
