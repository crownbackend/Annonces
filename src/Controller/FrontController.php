<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\User;
use App\Form\AdvertisementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

            $user = $this->getEmail();
            $username = $this->getUsername();

            $message = (new \Swift_Message('Mail de confirmation'))
                ->setFrom('annonces@lebonpoint.com')
                ->setTo($user)
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
}
