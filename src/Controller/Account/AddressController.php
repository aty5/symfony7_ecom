<?php

namespace App\Controller\Account;

use App\Class\Cart;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddressController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/addresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig', []);
    }

    #[Route('/account/address/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneBy(['id' => $id]);

        if (!$address or $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }

        $this->addFlash(
            'success',
            'Addresse correctement supprimé'
        );

        $this->entityManager->remove($address);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_account_addresses');
    }

    #[Route('/account/address/form/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
    public function form(Request $request,
                         $id,
                         AddressRepository $addressRepository,
                         Cart $cart): Response
    {
        if ($id)
        {
            $address = $addressRepository->findOneBy(['id' => $id]);

            if (!$address or $address->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('app_account_addresses');
            }
        } else
        {
            $address = new Address();
            $address->setUser($this->getUser());
        }


        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Addresse correctement enregistré'
            );

            if ($cart->fullQuantity() > 0) {
                return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/address/form.html.twig', [
            'addressForm' => $form,
        ]);
    }
}
