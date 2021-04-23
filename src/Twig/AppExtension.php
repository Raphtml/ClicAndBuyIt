<?php


namespace App\Twig;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;


class AppExtension extends AbstractExtension implements GlobalsInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGlobals(): array
    {
        return [
            'categories' => $this->em->getRepository(Category::class)->findAll()
        ];
    }
}