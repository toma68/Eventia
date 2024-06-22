<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Inscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); 

        // Générer des utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setName($faker->userName);

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        // Générer des événements
        for ($i = 0; $i < 35; $i++) {
            $event = new Event();
            $event->setTitle($faker->sentence);
            $event->setDescription($faker->paragraph);
            $event->setDate($faker->dateTimeBetween('-1 years', 'now'));
            $event->setMaxPeople($faker->numberBetween(10, 50));
            $event->setPublic($faker->boolean);
            $event->setCreator($this->getReference('user_' . $faker->numberBetween(0, 9)));

            $manager->persist($event);
            $this->addReference('event_' . $i, $event);
        }

        // Générer des inscriptions
        for ($i = 0; $i < 20; $i++) {
            $inscription = new Inscription();
            $inscription->setUser($this->getReference('user_' . $faker->numberBetween(0, 9)));
            $inscription->setEvent($this->getReference('event_' . $faker->numberBetween(0, 4)));

            $manager->persist($inscription);
        }

        $manager->flush();
    }
}
