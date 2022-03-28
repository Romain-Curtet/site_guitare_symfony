<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="order_cancel")
     */
    public function index($stripeSessionId, OrderRepository $orderRepository, EntityManagerInterface $manager): Response
    {
        $order = $orderRepository->findOneByStripeSessionId($stripeSessionId);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $mail = new Mail();
            $content = 'Bonjour '.$order->getUser()->getFirstname().'<br>Nous avons renontré un problème lors de votre paiement<br>
            Veuillez réessayer ultérieurement';
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), "Votre commande Tout Pour La Gratte n'a pu être validée", $content);
        
        return $this->render('order_cancel/index.html.twig', [
            'order' => $order,
        ]);
    }
}
