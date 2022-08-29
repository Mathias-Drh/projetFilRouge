<?php

namespace App\Controller\Profil;

use App\Entity\Post;
use App\Form\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*le controleur pour les posts quand ils ont des liens avec les profils*/
class PostController extends AbstractController
{

    #[Route('/post/add', name: 'app_post_add')]
    public function add(Request $req, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $roles = $this->getUser()->getRoles();
        $isAdmin = array_search('ADMIN', $roles);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            $message = "Votre bien a été ajouté !";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_all_posts');
        }
        return $this->renderForm('profil/post/addPost.html.twig', [
            'form' => $form,
            'isAdmin' => $isAdmin
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_post_edit', requirements: ['id' => '\d+'])]
    public function edit(PostRepository $postRepository, Request $req, EntityManagerInterface $entityManager, $id = null): Response
    {
        $post = $postRepository->find($id);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            $message = "Your post was edited !";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_all_posts');
        }
        return $this->renderForm('profil/post/addPost.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/delete/{id}', name: 'app_post_delete', requirements: ['id' => '\d+'])]
    public function delete(PostRepository $postRepository, EntityManagerInterface $entityManager, $id = null): Response
    {
        $post = $postRepository->find($id);

        $entityManager->remove($post);
        $entityManager->flush();
        $message = "Your post was removed !";
        $this->addFlash('success', $message);
        return $this->redirectToRoute('app_all_posts');
    }
}
