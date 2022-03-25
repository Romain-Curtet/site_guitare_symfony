<?php

namespace App\Controller;

use App\Classe\Mail;
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
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $notification = null;
        $error = null;
        $user = new User;

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $user->getPassword();
            
            $search_email = $userRepository->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );
                $user->setPassword($hashedPassword);
                $manager->persist($user);
                $manager->flush();

                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte";
            } else {
                $error = "L'Email que vous avez renseigné existe déjà";
            }
  
            $mail = new Mail();
            $content = "Bonjour ".$user->getFirstname()."<br>Bienvenue sur le site n°1 d'instruments et d'accessoires de musique";
            $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur Tout Pour La Gratte', $content);
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'error' => $error,
        ]);
    }
}
