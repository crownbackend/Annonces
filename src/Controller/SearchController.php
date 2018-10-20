<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\SearchType;
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
     * @throws \Exception
     */
    public function searchAction(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $region = 1;
            $category = 7;
            $value = $form->getData()->getTitle();

            $search = $this->getDoctrine()->getRepository(Advertisement::class)->findBySearch($value, $region, $category);

            return $this->render('search/result.html.twig', [
                'results' => $search
            ]);
        }

        return $this->render('search/search.html.twig', [
            'form' => $form->createView()
        ]);

    }

}