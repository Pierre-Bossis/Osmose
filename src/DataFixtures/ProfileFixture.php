<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profile;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setRs('Facebook');
        $profile->setUrl('https://www.facebook.com/pierre.bossis.5/');

        $profile1 = new Profile();
        $profile1->setRs('Twitter');
        $profile1->setUrl('https://twitter.com/OkamiNakagawa');

        $profile2 = new Profile();
        $profile2->setRs('GitHub');
        $profile2->setUrl('https://github.com/Pierre-Bossis/Osmose');

        $profile3 = new Profile();
        $profile3->setRs('LinkedIn');
        $profile3->setUrl('https://www.linkedin.com/in/pierre-bossis-421a98140/');

        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);
        $manager->flush();
    }
}
