<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login' , methods: 'POST')]
    public function index(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $username = $data['name'];
        $password = $data['password'];

        if ($username === 'admin' && $password === 'password') {
//            dd($username);
            $response = [
                'success' => true,
                'message' => 'Successful connection'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Username or password incorrect'
            ];
        }
        return $this->json($response);
    }
}
