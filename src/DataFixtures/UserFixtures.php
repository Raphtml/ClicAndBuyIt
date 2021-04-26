<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('Raphael')
            ->setLastname('Liere')
            ->setEmail('raphael.liere@hotmail.fr')
            ->setTelephone('0637112060')
            ->setPassword($this->encoder->encodePassword($user, 'password'));
        $manager->persist($user);
        $this->addReference('user', $user);
        $manager->flush();
    }
}
