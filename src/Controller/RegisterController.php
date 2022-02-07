<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User;

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
