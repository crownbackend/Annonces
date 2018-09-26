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
     * @Route("/annonces", name="back-advertisement", methods="GET")
     * @param Request $request
     * @return Response
     */
    public function advertisementAll(Request $request): Response {


        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Advertisement::class)->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        $advertisements = $this->getDoctrine()->getRepository(Advertisement::class)->findAll();

        return $this->render('back/all-advertisement.html.twig', [
            'advertisements' => $advertisements,
            'pagination' => $pagination
        ]);

    }
}
