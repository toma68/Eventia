<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ChangePasswordController extends AbstractController
{
    #[Route('/change/password', name: 'app_change_password')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordEncoder, Security $security): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $newPasswordVerif = $form->get('newPasswordVerif')->getData();

            if ($newPassword !== $newPasswordVerif) {
                $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
            } else {
                $user->setPassword(
                    $passwordEncoder->hashPassword(
                        $user,
                        $newPassword
                        )
                );
                $doctrine->getManager()->flush();

                $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');
                return $this->redirectToRoute('account');
            }
        }

        return $this->render('change_password/index.html.twig', [
            'accountForm' => $form->createView(),
        ]);
    }
}
