<?php

namespace App\Controller;

use App\Form\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*le controleur pour les posts quand ils n'ont pas de rapport avec les profils*/
class postController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function home(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('home.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/posts', name: 'app_all_posts')]
    public function showAll(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('posts.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/patch_notes', name: 'app_all_patch_notes')]
    public function showAllPatchNote(PostRepository $postRepository): Response
    {
        $patchNotes = $postRepository->findIfPatchNotes();

        return $this->render('posts.html.twig', [
            'posts' => $patchNotes
        ]);
    }

    #[Route('/post/{id}', name: 'app_one_post', requirements: ['id' => '\d+'])]
    public function showOne(PostRepository $postRepository, $id = null): Response
    {
        $post = $postRepository->find($id);

        return $this->render('post.html.twig', [
            'post' => $post
        ]);
    }
}