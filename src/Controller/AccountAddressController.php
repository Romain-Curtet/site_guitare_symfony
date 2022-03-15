<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/compte/adresses", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $manager->persist($address);
            $manager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request, EntityManagerInterface $manager, AddressRepository $addressRepository, $id): Response
    {
        $address = $addressRepository->findOneById($id);

        if (!$address || $address->getUser() != $this->getuser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_address_delete")
     */
    public function delete(EntityManagerInterface $manager, AddressRepository $addressRepository, $id): Response
    {
        $address = $addressRepository->findOneById($id);

        if ($address && $address->getUser() == $this->getuser()) {
            $manager->remove($address);
            $manager->flush();
        }

        return $this->redirectToRoute('account_address');
    }
}
