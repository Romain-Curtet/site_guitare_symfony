<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_success")
     */
    public function index(Cart $cart,$stripeSessionId, OrderRepository $orderRepository, EntityManagerInterface $manager): Response
    {
        $order = $orderRepository->findOneByStripeSessionId($stripeSessionId);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        
        if (!$order->getIsPaid()) {
           $cart->remove();
           $order->setIsPaid(1);
           $manager->flush();
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
