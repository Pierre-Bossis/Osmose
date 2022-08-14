<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hobby;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Yoga",
            "Cuisine",
            "Pâtisserie",
            "Photographie",
            "Blogging",
            "Lecture",
            "Apprendre une langue",
            "Construction Lego",
            "Dessin",
            "Coloriage",
            "Peinture",
            "Se lancer dans le tissage de tapis",
            "Créer des vêtements ou des coslay",
            "Fabriquer des bijoux",
            "Travailler le métal",
            "Décorer des galets",
            "Youtube",
            "Chanter",
            "Jouer aux fléchettes",
            "Voyager",
            "Cinéma",
            "Jeux vidéos"

        ];
        for($i=0;$i<count($data);$i++){
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
