<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 30; $i++)
        {
            $ad = new Ad();

            $ad->setTitle("Titre de l'annonce n°$i")
                ->setSlug("titre-de-l-annonce-n-$i")
                ->setCoverImage("http://placehold.it/1000x300")
                ->setIntroduction("Bonjour à tous. C'est une introduction")
                ->setContent("<p>Je suis un contenu riche !</p>")
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));
        
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
