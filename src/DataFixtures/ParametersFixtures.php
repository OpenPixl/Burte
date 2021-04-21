<?php

namespace App\DataFixtures;

use App\Entity\Admin\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParametersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $parameter = new Parameter();
        $parameter
            ->setNameSite('OpenGaia')
            ->setSloganSite('OpenGaia')
            ->setIsOnline(1)
            ->setDescrSite('Application de gestion')
        ;
        $manager->persist($parameter);

        $manager->flush();
    }
}
