<?php

namespace App\Controller;

use App\Form\UpdateAcountType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;






class AccountController extends AbstractController
{
   
    #[Route('/account', name: 'account', methods: ['POST', 'GET'])]
    public function account(Request $request, ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UpdateAcountType::class, $this->getUser());

        // si le formulaire est soumis
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('account');
        }

       return $this->render('account/index.html.twig', [
           'accountForm' => $form->createView()
       ]);
    }




    
}