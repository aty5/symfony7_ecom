<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [

        ]);
    }

    #[Route('/account/password', name: 'app_account_password')]
    public function password(Request                     $request,
                             UserPasswordHasherInterface $passwordHasher,
                             EntityManagerInterface      $entityManager): Response
    {

        $user = $this->getUser();

        $passwordForm = $this->createForm(
            PasswordUserType::class,
            $user,
            [
                'passwordHasher' => $passwordHasher,
            ]
        );

        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            //pas besoin de persist car n est pas un CREATE
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your password has been changed!'
            );
        }

        return $this->render('account/password.html.twig', [
            'password_form' => $passwordForm->createView(),
        ]);
    }

}
