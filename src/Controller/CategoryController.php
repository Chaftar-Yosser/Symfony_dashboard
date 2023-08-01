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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'category_index' ,  methods: 'GET')]
    public function index(): JsonResponse
    {
        $categories = $this->em->getRepository(Category::class)->findAll();

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'title' => $category->getTitle(),
            ];
        }
        return $this->json($data);
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

    #[Route('/showDetail/{id}', name: 'show_category' , methods: 'GET')]
    public function showDetailCategory(int $id): JsonResponse
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('No category found for id' . $id, 404);
        }

        $data =  [
            'id' => $category->getId(),
            'title' => $category->getTitle(),
        ];
        return $this->json($data);
    }

    #[Route('/edit/{id}', name: 'edit_category' , methods: 'PUT')]
    public function editCategory(Request $request, int $id)
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('No category found for id ' . $id, 404);
        }
//        dd($category->getId(),$category->getTitle());
//        $category->setTitle($request->request->get('title'));
        $data=json_decode($request->getContent(),true);
        $category->setTitle($data['title']);
        $this->em->persist($category);
        $this->em->flush();

        $data =  [
            'id' => $category->getId(),
            'title' => $category->getTitle(),
        ];

        return $this->json($data);
    }

    #[Route('/delete/{id}', name: 'delete_category' , methods: 'DELETE')]
    public function deleteCategory(Request $request, int $id)
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('No category found for id ' . $id, 404);
        }

        $this->em->remove($category);
        $this->em->flush();

        return $this->json('Deleted a category successfully with id ' . $id);
    }
}
