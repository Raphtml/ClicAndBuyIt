<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use App\Services\connectGoogleApiService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class AdvertController
 * @package App\Controller
 * @Route("/advert", name="advert_")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(AdvertRepository $advertRepository): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('home');
        }

        return $this->render('advert/index.html.twig', [
            'adverts' => $advertRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, connectGoogleApiService $googleApi): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('home');
        }

        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $geoloc = $googleApi->getCityLatLong($advert->getCity());
            $advert->setUser($this->getUser())
                ->setCreatedAt(New \DateTime('now', new \DateTimeZone('Europe/Paris')))
                ->setLatitude($geoloc['lat'])
                ->setLongitude($geoloc['lng']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($advert);
            $entityManager->flush();
            $this->addFlash('success', 'Votre annonce a bien été ajoutée');

            return $this->redirectToRoute('advert_index');
        }

        return $this->render('advert/new.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Advert $advert): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('advert_index');
        }

        return $this->render('advert/edit.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Advert $advert): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($advert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('advert_index');
    }

    /**
     * @Route("/{categoryName}/show", name="category")
     * @param AdvertRepository $advertRepository
     * @param Category $category
     * @return Response
     * @ParamConverter("category", options={"mapping": {"categoryName" : "name"}})
     */
    public function showByCategory(AdvertRepository $advertRepository, Category $category): Response
    {
        return $this->render('advert/index.html.twig', [
            'adverts' => $advertRepository->findBy(['category' => $category]),
        ]);
    }
}
