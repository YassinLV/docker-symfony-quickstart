<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_added")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/success", name="user_register_success")
     */
    public function success()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'success_message' => 'success message'
        ]);
    }

    /**
     * @Route("/list", name="users_list")
     */
    public function list()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users
        ]);
    }
}