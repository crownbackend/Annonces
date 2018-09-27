<?php

namespace App\Controller;

use App\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @package App\Controller
 */

class BackController extends Controller
{
    /**
     * @Route("/index", name="back-index")
     */
    public function index()
    {
        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->findAll();

        return $this->render('back/index.html.twig');
    }

    /**
     * @Route("/not-valid", methods="GET")
     * @return Response
     * @throws \Exception
     */
    public function notValidShow(): Response {

        $notValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByCountNotValid();
        return $this->render('back/notvalid.html.twig', [
            'notValid' => $notValid
        ]);
    }

    /**
     * @Route("/annonces", name="back-advertisement", methods="GET")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function advertisementAll(Request $request): Response {

        $count = $this->getDoctrine()->getRepository(Advertisement::class)->findByCount();
        $isValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByCountValid();
        $notValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByCountNotValid();

        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Advertisement::class)->findBy([], ['createdAt' => 'desc']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('back/all-advertisement.html.twig', [
            'pagination' => $pagination,
            'count' => $count,
            'isValid' => $isValid,
            'notValid' => $notValid
        ]);

    }
}
