<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Front controller this controller manages all the party front of the website

class AnnexController extends Controller
{
    /**
     * @Route("/dc/vos_droits_et_obligations", name="obligations")
     * @return Response
     */
    public function obligations(): Response {

        return $this->render('front/obligations.html.twig');
    }

}