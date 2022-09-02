<?php

namespace App\Controller\Profil;

use App\Entity\User;
use App\Form\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $userRepository): Response
    {
        $connectedUser = $userRepository->find($this->getUser());

        return $this->render('profil/index.html.twig', [
            'connectedUser' => $connectedUser
        ]);
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function add(Request $req, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->hasher->hashPassword($user, $form->get('password')->getData()));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            $message = "Vous avez été ajouté !";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_profil');
        }
        return $this->renderForm('profil/addUser.html.twig', [
            'form' => $form
        ]);
    }
}
