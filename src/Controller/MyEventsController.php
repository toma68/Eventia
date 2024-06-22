<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyEventsController extends AbstractController
{
    #[Route('/user/events', name: 'app_my_events')]
    public function index(): Response
    {
        $events = [];
        $user = $this->getUser();
        $inscriptions = $user->getInscriptions();
        foreach ($inscriptions as $inscription) {
            $events[] = $inscription->getEvent();
        }

        //on rajoute les événements créés par l'utilisateur
        $events = array_merge($events, $user->getEvents()->toArray());
        $events = array_unique($events, SORT_REGULAR);




        return $this->render('my_events/index.html.twig', [
            'events' => $events,
        ]);

    }
}
