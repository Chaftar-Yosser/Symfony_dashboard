<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/category')]
class CategoryController extends AbstractController
{

    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
//        $this->repository=
    }

    #[Route('/', name: 'category_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CategoryController.php',
        ]);
    }

    #[Route('/create', name: 'create_category' , methods: 'POST')]
    public function createCategory(Request $request)
    {
        $category = new Category();
        $data=json_decode($request->getContent(),true);
        $category->setTitle($data['title']);
        $this->em->persist($category);
        $this->em->flush();
        return $this->json([
            'message' => 'Category crée avec succés!',
            'code' => 201,
        ]);

    }
}
