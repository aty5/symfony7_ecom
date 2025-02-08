<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/password', name: 'app_account_password')]
    public function index(Request $request,
                          UserPasswordHasherInterface $passwordHasher): Response
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
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Your password has been changed!'
            );
        }

        return $this->render('account/password/index.html.twig', [
            'password_form' => $passwordForm->createView(),
        ]);
    }
}
