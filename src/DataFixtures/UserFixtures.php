<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr');
        $user = new User();
        $user->setFirstname('Raphael')
            ->setLastname('Liere')
            ->setEmail('raphael.liere@hotmail.fr')
            ->setAddress('3 Place Emile Fayard')
            ->setZipCode('69270')
            ->setCity('Couzon au mont d\'or')
            ->setTelephone('0637112060')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setLatitude($faker->latitude)
            ->setLongitude($faker->longitude);

        $manager->persist($user);

        $this->addReference('user', $user);

        $manager->flush();
    }
}
