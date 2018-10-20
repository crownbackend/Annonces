<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}")
 */

class FrontController extends Controller {

    /**
     * Home page
     * @Route({"fr": "/", "en": "/", "es": "/"},
     *     name="index", methods="GET",
     *     requirements={"_locale" = "fr|en|es"},
     *     defaults = {"_locale" = "fr"})
     * @return Response
     * @throws \Exception
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
     * @Route({"fr": "/annonces/{regionSlug}",
     *         "en": "/advertisement/{regionSlug}",
     *         "es": "/anuncio/{regionSlug}"}, name="region", methods="GET|POST")
     * @param string $regionSlug
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function regionShow(string $regionSlug, Request $request): Response {

        $isValid = 1;

        $regions = $this->getDoctrine()->getRepository(Region::class)->findBySlugRegion($regionSlug);
        // get region and all advertisement in region
        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Advertisement::class)->findByRegions($regionSlug, $isValid);
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

}