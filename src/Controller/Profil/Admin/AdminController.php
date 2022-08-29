<?php

namespace App\Controller\Profil\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\Form\PostType;
use App\Form\Form\UserType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/admin', name: 'app_admin')]
    public function administrator(PostRepository $postRepository, UserRepository $userRepository): Response
    {
        $posts = $postRepository->findAll();
        $users = $userRepository->findAll();

        return $this->render('profil/admin/admin.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);
    }

    #[Route('/admin/post/add', name: 'app_admin_post_add')]
    public function addPostAsAdmin(Request $req, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            $message = "Votre bien a été ajouté !";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_all_posts');
        }
        return $this->renderForm('profil/post/addPost.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/user/add', name: 'app_admin_user_add')]
    public function addUserAsAdmin(Request $req, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->hasher->hashPassword($user, $form->get('password')->getData()));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            $message = "Votre user a été ajouté !";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin');
        }
        return $this->renderForm('profil/addUser.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit', requirements: ['id' => '\d+'])]
    public function edit(UserRepository $userRepository, Request $req, EntityManagerInterface $entityManager, $id = null): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->hasher->hashPassword($user, $form->get('password')->getData()));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            $message = "Votre user a été modifié !";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin');
        }
        return $this->renderForm('profil/addUser.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete')]
    public function delete(UserRepository $userRepository, EntityManagerInterface $entityManager, $id = null): Response
    {
        $agent = $userRepository->find($id);

        $entityManager->remove($agent);
        $entityManager->flush();
        $message = "Votre agent a été supprimé !";
        $this->addFlash('success', $message);
        return $this->redirectToRoute('app_admin');
    }
}
