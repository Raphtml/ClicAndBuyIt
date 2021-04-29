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
        for ($i=0;$i<20;$i++){
            $like = new AdvertLike();
            $like->setCreatedAt(new \DateTime())
                ->setUser($this->getReference('user'))
                ->setAdvert($this->getReference('advert_' . $i));

            $manager->persist($like);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            AdvertFixtures::class
        ];
    }
}