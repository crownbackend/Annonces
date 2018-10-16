<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */

class SearchController extends Controller
{
    /**
     * @Route({"fr": "/recherche/",
     *         "en": "/search/",
     *         "es": "/buscar/"}, name="search", methods="POST|GET")
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request): Response
    {


        return $this->render('search/search.html.twig');

    }

}