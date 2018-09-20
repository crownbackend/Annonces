<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\ReasonOfDealt;
use App\Entity\Region;
use App\Form\AdvertisementType;
use App\Form\ReasonOfDealtType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Front controller this controller manages all the party front of the website

class FrontController extends Controller
{
    /**
     * Home page
     * @Route("/", name="index", methods="GET")
     * @return Response
     */
    public function index(): Response
    {
        // Count advertisement total
        $count = $this->getDoctrine()->getRepository(Advertisement::class)->findByCount();
        // get all regions
        $regions = $this->getDoctrine()->getRepository(Region::class)->findAll();

        return $this->render('front/index.html.twig', [
            'count' => $count,
            'regions' => $regions
        ]);
    }

    /**
     * add new advertisement
     * @Route("/annonces/ajouter-une-annonce", name="add-advertisement")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function addAdvertisement(Request $request, \Swift_Mailer $mailer): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // treatment and persist new advertisement
            $advertisement = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $advertisement->setUser($user);
            $manager->persist($advertisement);
            $manager->flush();

            //send a confirmation email to the user
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
            return $this->redirectToRoute('my-advertisement');
        }
        return $this->render('advertisement/add-advertisement.html.twig', [
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
        // get region and all advertisement in region
        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Advertisement::class)->findByRegions($regionSlug);
        // pagination 3 by page
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('front/regions.html.twig', [
            'pagination' => $pagination,
            'regions' => $regions
        ]);

    }

    /**
     * get category and advertisement
     * @Route("/offres/{categorySlug}/{advertisementSlug}", name="advertisement")
     * @param string $advertisementSlug
     * @param string $categorySlug
     * @return Response
     */
    public function advertisementShow(string $advertisementSlug, string $categorySlug): response{
        $isValid = 1;
        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)
            ->findBySlugAdvertisement($advertisementSlug, $categorySlug, $isValid);

        return $this->render('advertisement/advertisement.html.twig', [
            'advertisement' => $advertisement
        ]);
    }

    /**
     * get advertisement from the connected user
     * @Route("/mon-compte/mes-annonces", name="my-advertisement")
     * @return Response
     */
    public function myAdvertismentShow(): Response {

        $isValid = 1;
        $notValid = 0;
        $userCurrent = $this->getUser();

        $advertisementsValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByMyAdvertisementValid($userCurrent, $isValid);
        $advertisementsNotValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByMyAdvertisementNotValid($userCurrent, $notValid);

        $countValid = $this->getDoctrine()->getRepository(Advertisement::class)
            ->findByCountMyAdvertisementValid($userCurrent, $isValid); // count advertisement valid
        $countNotValid = $this->getDoctrine()->getRepository(Advertisement::class)
        ->findByCountMyAdvertisementNotValid($userCurrent, $notValid); // count advertisement not Valid

        return $this->render('advertisement/my-advertisement.html.twig', [
            'advertisementsValid' => $advertisementsValid,
            'advertisementsNotValid' => $advertisementsNotValid,
            'countActive' => $countValid,
            'countNotActive' => $countNotValid
        ]);
    }

    /**
     * edit advertisement
     * @Route("/mon-compte/mes-annonces/editer/{id}", name="my-advertisement-edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function myAdvertisementEdit(Request $request, int $id, \Swift_Mailer $mailer): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $email = $this->getUser()->getEmail();
            $username = $this->getUser()->getUsername();

            $advertisement = $form->getData();
            // the bool isValid set 0
            $advertisement->setIsvalid(0);
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $this->addFlash('edit-my-advertisement', 'Votre demande de modification à bien été prise 
            en compte il faut compter environs 24h pour se faire valider une 
            annonce déja posté(l\'annonce sera pas visible pendant les prochaine 24h !)');
            // send mail in edit advertisement
            $message = (new \Swift_Message('Demande de modification le bon point'))
                ->setFrom('annonces@lebonpoint.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/modification.html.twig',
                        [
                            'username' => $username,
                            'advertisement' => $advertisement
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->redirectToRoute('my-advertisement');
        }
        return $this->render('advertisement/edit-my-advertisement.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * confirm in delete advertisement
     * @Route("/mon-compte/mes-annonces/confirmation-suppression/{id}", name="confirm-delete")
     * @param int $id
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     */
    public function confirmDelete(int $id, Request $request, \Swift_Mailer $mailer): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);

        $raisonOfDealt = new ReasonOfDealt();
        $form = $this->createForm(ReasonOfDealtType::class, $raisonOfDealt);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $raisonOfDealt = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($raisonOfDealt);
            $manager->flush();

            //send a confirmation email to the user
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
        }

        return $this->render('advertisement/confirm-delete.html.twig', [
            'confirmationDelete' => $advertisement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mon-compte/mes-annonces/suppression/{id}", name="delete-advertisement")
     * @param int $id
     * @return Response
     */
    public function deleteAdvertisement(int $id): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);

        $delete = $this->getDoctrine()->getManager();
        $delete->remove($advertisement);
        $delete->flush();
        $this->addFlash('delete', 'L\'annonce à bien étais supprimé !');

        return $this->redirectToRoute('my-advertisement');
    }





























}
