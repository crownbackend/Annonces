<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Region;
use App\Form\AdvertisementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
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
    public function addAdvertisement(Request $request, \Swift_Mailer $mailer): Response
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
            $email = $this->getUser()->getEmail();
            $username = $this->getUser()->getUsername();

            $message = (new \Swift_Message('Mail de confirmation le bon point'))
                ->setFrom('annonces@lebonpoint.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/confirmation.html.twig',
                        [
                            'username' => $username
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            return $this->redirectToRoute('index');
        }

        return $this->render('front/add-advertisement.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonces/{slug}/{id}", name="region")
     * @param string $slug
     * @param int $id
     * @return Response
     */
    public function region(string $slug, int $id): Response {

        $regions = $this->getDoctrine()->
        getRepository(Region::class)->
        findBySlugRegion($slug);

        $addvertisements = $this->getDoctrine()->
        getRepository(Advertisement::class)->
        findByRegions($id);

        return $this->render('front/regions.html.twig', [
            'regions' => $regions,
            'addvertisements' => $addvertisements
        ]);

    }





























}
