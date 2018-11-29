<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Category;
use App\Entity\Region;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/{_locale}")
 */

class SearchController extends Controller
{
    /**
     * @Route({"fr": "/recherche/{value}/{region}/{category}",
     *         "en": "/search/",
     *         "es": "/buscar/"}, name="search", methods="GET")
     * @ParamConverter("region", options={"mapping":{"region":"id"}})
     * @param Request $request
     * @param string $value
     * @param Region $region
     * @param Category $category
     * @return Response
     * @throws \Exception
     */
    public function searchAction(Request $request, string $value, Region $region, Category $category): Response
    {
        $form = $this->createForm(SearchType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $value = $form->getData();
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