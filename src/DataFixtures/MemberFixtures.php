<?php

namespace App\DataFixtures;

use App\Entity\Admin\member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $member = new Member();
        $member->setRoles(array('ROLE_ADMIN'));
        $member->setFirstName('admin');
        $member->setLastName('Dév');
        $member->setPassword($this->passwordEncoder->encodePassword($member,'admin123'));
        $member->setAdress1('Maison des association');
        $member->setAdress2('40 impasse Georges SABDE');
        $member->setZipcode('40280');
        $member->setCity('Saint Pierre du Mont');
        $member->setPhoneDesk('00.00.00.00.00');
        $member->setPhoneGsm('00.00.00.00.00');
        $manager->persist($member);

        $member = new Member();
        $member->setRoles(array('ROLE_ADMIN'));
        $member->setFirstName('utilisateur 1');
        $member->setLastName(' testeur 1');
        $member->setPassword($this->passwordEncoder->encodePassword($member,'test123'));
        $member->setAdress1('Maison des association');
        $member->setAdress2('40 impasse Georges SABDE');
        $member->setZipcode('40280');
        $member->setCity('Saint Pierre du Mont');
        $member->setPhoneDesk('00.00.00.00.00');
        $member->setPhoneGsm('00.00.00.00.00');
        $manager->persist($member);

        $manager->flush();
    }
}
