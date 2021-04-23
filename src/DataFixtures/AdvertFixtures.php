<?php


namespace App\DataFixtures;


use App\Entity\Advert;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AdvertFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr');

        for ($i=0; $i<20; $i++){
            $advert = new Advert();
            $advert->setTitle($faker->sentence())
                ->setDescription($faker->text())
                ->setCreatedAt(new \DateTime())
                ->setPhoto($faker->imageUrl())
                ->setUser($this->getReference('user'))
                ->setCategory($this->getReference('category_' . rand(0, 9)));

            $this->addReference('advert_' . $i, $advert);

            $manager->persist($advert);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class
        ];
    }
}