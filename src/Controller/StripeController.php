<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index($reference, OrderRepository $orderRepository, ProductRepository $productRepository, EntityManagerInterface $manager): Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';

        $order = $orderRepository->findOneByReference($reference);

        if (!$order) {
            return $this->redirectToRoute('order');
        } else {
            foreach ($order->getOrderDetails()->getValues() as $product) {
                $product_object = $productRepository->findOneByName($product->getProduct());
                $products_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $product->getPrice(),
                        'product_data' => [
                            'name' => $product->getProduct(),
                            'images' => [$YOUR_DOMAIN.'/uploads/'.$product_object->getIllustration()],
                        ],
                    ],
                    'quantity' => $product->getQuantity(),
                ];
            }

            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $order->getCarrierPrice(),
                    'product_data' => [
                        'name' => $order->getCarriername(),
                        'images' => [$YOUR_DOMAIN],
                    ],
                ],
                'quantity' => 1,
            ];

            Stripe::setApiKey('sk_test_51Kfg1cJiopwlb2XKuvZq4Ex78OwvbiryV2xVA3w8O9NN1F2sug1gwVh0KYrbEkn4b2C0FZf4IlgwKVE0Yq3IjyR400BFZEFPKO');

                $checkout_session = Session::create([
                    'customer_email' => $this->getUser()->getEmail(),
                    'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
                    'mode' => 'payment',
                    'line_items' => [
                        $products_for_stripe
                    ],
                ]);

                $order->setStripeSessionId($checkout_session->id);
                $manager->flush();

                return $this->redirect($checkout_session->url);
        }
    }
}
