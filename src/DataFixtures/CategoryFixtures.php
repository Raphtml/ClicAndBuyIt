<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            'Immobilier',
            'Véhicules',
            'Mode',
            'Maison',
            'Multimédia',
            'Loisirs',
            'Animaux',
            'Services'
        ];

        for ($i=0;$i<count($categories);$i++){
            $category = new Category();
            $category->setName($categories[$i]);
            $manager->persist($category);
            $this->addReference('category_' . $i, $category);
        }

        $manager->flush();
    }
}