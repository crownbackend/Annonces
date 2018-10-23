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
     * @Route({"fr": "/recherche/search/?value={value}&region={region}&category={category}",
     *         "en": "/search/",
     *         "es": "/buscar/"}, name="search", methods="GET")
     * @param Request $request
     * @param string $value
     * @param string $region
     * @param string $category
     * @return Response
     * @throws \Exception
     */
    public function searchAction(Request $request, string $value, string $region, string $category): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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