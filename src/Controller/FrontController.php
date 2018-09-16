<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Category;
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
     * @Route("/annonces/{regionSlug}/{id}", name="region")
     * @param string $regionSlug
     * @param int $id
     * @return Response
     */
    public function regionShow(string $regionSlug, int $id): Response {

        $regions = $this->getDoctrine()->getRepository(Region::class)->findBySlugRegion($regionSlug);
        $advertisements = $this->getDoctrine()->getRepository(Advertisement::class)->findByRegions($id);

        return $this->render('front/regions.html.twig', [
            'regions' => $regions,
            'advertisements' => $advertisements
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
