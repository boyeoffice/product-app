<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

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
    public function store(Request $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->request->all());

        return $this->json([
            'message' => 'Product created successfully.',
            'data' => $product
        ],201);
    }
}
