<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\User;
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
     * @return Response
     */
    public function index(): Response
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

        // globals count not valid
        $notValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByCountNotValid();
        return $this->render('back/notvalid.html.twig', [
            'notValid' => $notValid
        ]);
    }

    /**
     * @Route("/annonces/tout-les-annonces", name="back-advertisement", methods="GET")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function advertisementAll(Request $request): Response {

        // count all advertisement
        $count = $this->getDoctrine()->getRepository(Advertisement::class)->findByCount();
        // not valid count
        $isValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByCountValid();
        // valid count
        $notValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByCountNotValid();
        // Pagination 12 in 1 page
        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Advertisement::class)->findBy([], ['createdAt' => 'desc']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );
        // return the params
        return $this->render('back/all-advertisement.html.twig', [
            'pagination' => $pagination,
            'count' => $count,
            'isValid' => $isValid,
            'notValid' => $notValid
        ]);

    }

    /**
     * @Route("/annonces/tout-les-annonces/annonces-non-valider", name="back-advertisement-notValid", methods="GET")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function allAdvertisementNotValidShow(Request $request): Response {
        // isValid 0 = notValid
        $isValid = 0;
        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Advertisement::class)->findAllNotValid($isValid);
        //Paginatione
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('back/all-advertisement-notvalid.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/utilisateurs/tout-lestilisateurs", name="back-all-users")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function allUserShow(Request $request): Response {

        $count = $this->getDoctrine()->getRepository(User::class)->findByCount();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(User::class)->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('back/all-user.html.twig', [
            'count' => $count,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/annonces/detail-annonces/id={id}", name="back-detail-advertisement", methods="GET")
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function advertisementDetailShow(int $id): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);

        return $this->render('back/advertisement-detail.html.twig', [
            'advertisement' => $advertisement
        ]);
    }










}
