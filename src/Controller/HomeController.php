<?php


namespace App\Controller;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param AdvertRepository $advertRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(AdvertRepository $advertRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $advertRepository->findAll();
        $adverts = $paginator->paginate(
            $data,
            $request->query->get('page', 1),
            4
        );

        return $this->render('home/index.html.twig', [
            'adverts' => $adverts
        ]);
    }

}