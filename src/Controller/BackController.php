<?php

namespace App\Controller;

use App\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return Response
     */
    public function advertisementAll(): Response {

        $advertisements = $this->getDoctrine()->getRepository(Advertisement::class)->findAll();

        return $this->render('back/all-advertisement.html.twig', [
            'advertisements' => $advertisements
        ]);

    }
}
