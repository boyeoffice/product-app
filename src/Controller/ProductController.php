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
                'description' => $product->getDescription(),
                'quantity' => $product->getQuantity()
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

    #[Route('/products/{id}', name: 'product_show', methods: 'GET')]
    public function show (EntityManagerInterface $em, int $id): JsonResponse
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (is_null($product)):
            return $this->json([
               'message' => 'Product not found.'
            ], 404);
        endif;

        return $this->json([
            'message' => 'Product retrieved successfully.',
            'data' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'quantity' => $product->getQuantity()
            ]
        ], 200);
    }

    #[Route('/products/{id}', name: 'product_update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, EntityManagerInterface $em, int $id): JsonResponse
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (is_null($product)):
            return $this->json([
               'message' => 'Product not found.'
            ], 404);
        endif;

        $data = $request->request->all();
        $product->setName($data['name']?? $product->getName());
        $product->setDescription($data['description']?? $product->getDescription());
        $product->setQuantity($data['quantity']?? $product->getQuantity());

        $em->persist($product);
        $em->flush();

        return $this->json([
           'message' => 'Product updated successfully.',
            'data' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'quantity' => $product->getQuantity()
            ]
        ], 200);
    }

    #[Route('/products/{id}', name: 'product_delete', methods: 'DELETE')]
    public function delete(EntityManagerInterface $em, $id)
    { 
        $product = $em->getRepository(Product::class)->find($id);

        if (is_null($product)) {
            return $this->json([
                'message' => 'No product found for id '.$id
            ], 404);
        }

        $em->remove($product);
        $em->flush();

        return $this->json([
           'message' => 'Product deleted successfully.'
        ], 200);
    }
}
