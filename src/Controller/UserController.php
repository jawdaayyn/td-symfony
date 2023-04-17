<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }


    #[Route('/user/{id}', name: 'user_show')]
    public function show(User $user): Response
    {
        return $this->render("user/show.html.twig", [
            "user" => $user
        ]);
    }

    
    #[Route('/user-create', name: 'user_create')]
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$userRepository->persist($user);
            //$userRepository->flush();

            $userRepository->save($user, true);

            return $this->redirectToRoute('user_index');
        }
        
        return $this->render('user/create.html.twig', [
            'users' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'user_edit')]
    public function edit(User $user, Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$userRepository->persist($user);
            //$userRepository->flush();

            $userRepository->save($user, true);

            return $this->redirectToRoute('user_index');
        }
        
        return $this->render('user/edit.html.twig', [
            'users' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'user_delete')]
    public function delete(User $user, UserRepository $userRepository): Response
    {
            $userRepository->remove($user, true);
            return $this->redirectToRoute('user_index');
    }

    
    #[Route('/user-articles/{id}', name: 'user_article')]
    public function showArticles (User $user): Response
    {

        return $this->render("user/article.html.twig", [
            "articles" => $user->getArticles()
        ]);
    }
}
