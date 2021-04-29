<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\AdvertLike;
use App\Entity\Category;
use App\Form\AdvertType;
use App\Repository\AdvertLikeRepository;
use App\Repository\AdvertRepository;
use App\Services\connectGoogleApiService;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request, connectGoogleApiService $googleApi, SlugifyInterface $slugify): Response
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
                ->setLongitude($geoloc['lng'])
                ->setSlug($slugify->slugify($advert->getTitle()));
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
     * @Route("/saved", name="saved")
     * @param AdvertLikeRepository $likeRepo
     * @return Response
     */
    public function advertLiked(AdvertLikeRepository $likeRepo):Response
    {
        $adverts = [];
        $likeAdverts = $likeRepo->findBy(['user' => $this->getUser()]);
        foreach ($likeAdverts as $like){
            $adverts[] = $like->getAdvert();
        }
        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts
        ]);
    }

    /**
     * @Route("/{slug}", name="show", methods={"GET"})
     */
    public function show(Advert $advert): Response
    {
        $bool = 0;
        foreach ($advert->getLikes() as $like){
            if ($like->getUser() == $this->getUser()){
                $bool += 1;
            }
        }
        return $this->render('advert/show.html.twig', [
            'bool' => $bool,
            'advert' => $advert,
        ]);
    }


    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET", "POST"})
     * @throws \Exception
     */
    public function edit(Request $request, Advert $advert): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advert->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
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
            $this->addFlash('success', 'Votre annonce a été supprimée');
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

    /**
     * @Route("/like/{id}", name="like", methods={"POST"})
     * @param Advert $advert
     * @param EntityManagerInterface $manager
     * @param AdvertLikeRepository $likeRepo
     * @return Response
     */
    public function like(Advert $advert, EntityManagerInterface $manager, AdvertLikeRepository $likeRepo): Response
    {
        $user = $this->getUser();

        if (!$user){
            return $this->json(["message" => "Vous devez être connecter pour sauvegarder une annonce"], 403);
        }

        if ($advert->isLikedByUser($this->getUser())){
            $like = $likeRepo->findOneBy([
                'advert' => $advert,
                'user' => $this->getUser()
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json(['message' => 'Le like a été supprimé'],200);
        }

        $like = new AdvertLike();
        $like->setUser($this->getUser())
            ->setAdvert($advert)
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $manager->persist($like);
        $manager->flush();

        return $this->json(['message' => 'Le like a été ajouté'], 200);
    }
}
