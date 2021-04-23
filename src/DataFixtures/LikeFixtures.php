<?php


namespace App\DataFixtures;


use App\Entity\AdvertLike;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<100;$i++){
            $like = new AdvertLike();
            $like->setCreatedAt(new \DateTime())
                ->setUser($this->getReference('user'))
                ->setAdvert($this->getReference('advert_' . rand(0, 19)));

            $manager->persist($like);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AdvertFixtures::class
        ];
    }
}