<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Region;
use App\Form\AdvertisementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends Controller
{
    /**
     * @Route("/", name="index", methods="GET")
     * @return Response
     */
    public function index(): Response
    {
        $count = $this->getDoctrine()->getRepository(Advertisement::class)->findByCount();
        $regions = $this->getDoctrine()->getRepository(Region::class)->findAll();

        return $this->render('front/index.html.twig', [
            'count' => $count,
            'regions' => $regions
        ]);
    }

    /**
     * @Route("/annonces/ajouter-une-annonce", name="add-advertisement")
     * @return Response
     */
    public function addAdvertisement(Request $request, \Swift_Mailer $mailer): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $advertisement = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $advertisement->setUser($user);
            $manager->persist($advertisement);
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
     * @Route("/annonces/{regionSlug}", name="region")
     * @param string $regionSlug
     * @param Request $request
     * @return Response
     */
    public function regionShow(string $regionSlug, Request $request): Response {

        $regions = $this->getDoctrine()->getRepository(Region::class)->findBySlugRegion($regionSlug);
        $advertisements = $this->getDoctrine()->getRepository(Advertisement::class)->findByRegions($regionSlug);
/*
        $em    = $this->getDoctrine()->getManager();
        $dql   = "SELECT a FROM App:Advertisement a WHERE a.isValid = :bool AND :region = a.region ORDER BY a.createdAt DESC";
        $query = $em->createQuery($dql);
        $query->setParameter('bool', 1);
        $query->setParameter('region', $id);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(

            $request->query->getInt('page', 1),
            10
        );
*/
        return $this->render('front/regions.html.twig', [
            'advertisements' => $advertisements,
            'regions' => $regions
        ]);

    }

    /**
     * @Route("/offres/{categorySlug}/{advertisementSlug}", name="advertisement")
     * @param string $advertisementSlug
     * @param string $categorySlug
     * @return Response
     */
    public function advertisementShow(string $advertisementSlug, string $categorySlug): response{

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->findBySlugAdvertisement($advertisementSlug, $categorySlug);

        return $this->render('front/advertisement.html.twig', [
            'advertisement' => $advertisement
        ]);
    }





























}
