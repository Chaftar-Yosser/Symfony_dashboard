<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/', name: 'product_index' ,  methods: 'GET')]
    public function index(): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'description' =>$product->getDescription(),
                'nbr_stock' =>$product->getNbrStock(),
                'price' =>$product->getPrice(),
                'image' =>$product->getImage(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/create', name: 'create_product' , methods: 'POST')]
    public function createProduct(Request $request)
    {
        $product = new Product();
        $data=json_decode($request->getContent(),true);
        $product->setTitle($data['title']);
        $product->setDescription($data['description']);
        $product->setNbrStock($data['nbr_stock']);
        $product->setPrice($data['price']);
        $product->setImage($data['image']);

        $this->em->persist($product);
        $this->em->flush();
        return $this->json([
            'message' => 'Product crée avec succés!',
            'code' => 201,
        ]);

    }

    #[Route('/showDetail/{id}', name: 'show_product' , methods: 'GET')]
    public function showDetailProduct(int $id): JsonResponse
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No project found for id' . $id, 404);
        }

        $data =  [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' =>$product->getDescription(),
            'nbr_stock' =>$product->getNbrStock(),
            'price' =>$product->getPrice(),
            'image' =>$product->getImage(),
        ];
        return $this->json($data);
    }

    #[Route('/edit/{id}', name: 'edit_product' , methods: 'PUT')]
    public function editProduct(Request $request, int $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No category found for id ' . $id, 404);
        }

        $data=json_decode($request->getContent(),true);
        $product->setTitle($data['title']);
        $product->setDescription($data['description']);
        $product->setNbrStock($data['nbr_stock']);
        $product->setPrice($data['price']);
        $product->setImage($data['image']);
        $this->em->persist($product);
        $this->em->flush();

        $data =  [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' =>$product->getDescription(),
            'nbr_stock' =>$product->getNbrStock(),
            'price' =>$product->getPrice(),
            'image' =>$product->getImage(),
        ];

        return $this->json($data);
    }

    #[Route('/delete/{id}', name: 'delete_product' , methods: 'DELETE')]
    public function deleteProduct(Request $request, int $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No product found for id ' . $id, 404);
        }

        $this->em->remove($product);
        $this->em->flush();

        return $this->json('Deleted a product successfully with id ' . $id);
    }
}
