<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: 'GET')]
    public function index(EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $products = $entityManagerInterface->getRepository(Product::class)->findAll();

        $data = [];

        foreach ($products as $product):
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription()
            ];
        endforeach;
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'data' => $data,
        ]);
    }

    #[Route('/products', name: 'product_store', methods: 'POST')]
    public function store(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setQuantity($request->request->get('quantity'));
        $product->setDescription($request->request->get('description'));

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
