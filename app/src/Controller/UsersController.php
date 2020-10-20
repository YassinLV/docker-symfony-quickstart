<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\NumberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="user_added", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @param NumberService $numberService
     * @return Response
     */
    public function new(Request $request, NumberService $numberService): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setCountryCode($form->getData()->getCountry());
            $internationalNumber = $numberService->getInternationNumberFromApi($user);
            $user->setInternationalNumber($internationalNumber);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_register_success');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success", name="user_register_success")
     */
    public function success()
    {
        return $this->render('user/success.html.twig', [
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
