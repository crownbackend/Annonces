<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\AdvertisementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/annonces/ajouter-une-annonce", name="add-advertisement")
     * @return Response
     */
    public function addAdvertisement(Request $request): Response
    {
        $addvertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $addvertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $addvertisement = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $addvertisement->setUser($user);
            $manager->persist($addvertisement);
            $manager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('front/add-advertisement.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
