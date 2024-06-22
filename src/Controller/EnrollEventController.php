<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;
use App\Entity\Inscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;

class EnrollEventController extends AbstractController
{
    #[Route('/enroll/event/{event_id}', name: 'app_enroll_event')]
    public function enroll($event_id, EntityManagerInterface $entityManager, MailerInterface $mailer, Request $request): Response
    {
        $user = $this->getUser();
        $event = $entityManager->getRepository(Event::class)->find($event_id);

        if ( $event->getMaxpeople() <= count($event->getInscriptions()) ) {
            $this->addFlash('red', 'Event plein !');
            return $this->redirectToRoute('app_event_index');
        }

        $inscription = new Inscription();
        $inscription->setUser($user);
        $inscription->setEvent($event);

        $event->addInscription($inscription);
        $user->addInscription($inscription);

        $entityManager->persist($inscription);


        $entityManager->flush();

        //envoyer un mail
        $email = (new Email())
            ->from('NoReply@thomas-dev.com')
            ->to($user->getEmail())
            ->subject('Inscription à un événement')
            ->text('Vous êtes inscrit à l\'événement ' . $event->getTitle());
        
        $mailer->send($email);
        


        return $this->redirect($request->headers->get('referer'));



    }

    #[Route('/unroll/event/{event_id}', name: 'app_unroll_event')]
    public function unroll($event_id, EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer
          ): Response
    {
        // on supprime une entrée dans la table inscriptions
        $user = $this->getUser();
        $event = $entityManager->getRepository(Event::class)->find($event_id);

       // on supprime l'inscription
        $inscription = $entityManager->getRepository(Inscription::class)->findOneBy([
            'user' => $user,
            'event' => $event
        ]);


        $entityManager->remove($inscription);
        $entityManager->flush();

        //envoyer un mail
        $email = (new Email())
            ->from('NoReply@thomas-dev.com')
            ->to($user->getEmail())
            ->subject('Désinscription à un événement')
            ->text('Vous êtes désinscrit à l\'événement ' . $event->getTitle());
        
        $mailer->send($email);

        return $this->redirect($request->headers->get('referer'));

    }
}
