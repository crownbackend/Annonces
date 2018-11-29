<?php

namespace App\Controller;


use App\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    /**
     * @Route("/advertisement", name="api-advertisement", methods="GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function showAdvertisement(Request $request): JsonResponse
    {
        $advertisements = $this->getDoctrine()->getRepository(Advertisement::class)->findAll();
        /**
         * @var $advertisement Advertisement
         */
        $formatted = [];
        foreach ($advertisements as $advertisement) {
            $formatted[] = [
                'id' => $advertisement->getId(),
                'title' => $advertisement->getTitle(),
                'description' => $advertisement->getDescription(),
                'price' => $advertisement->getPrice(),
                'address' => $advertisement->getAddress(),
                'isValid' => $advertisement->getIsValid(),
                'createdAt' => $advertisement->getCreatedAt(),
                'imageName' => $advertisement->getImageName(),
                'category' => $advertisement->getCategory()->getName(),
                'region' => $advertisement->getRegion()->getName()
            ];
        }

        return new JsonResponse($formatted, 200, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'));

    }
}