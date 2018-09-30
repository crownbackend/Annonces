<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\User;
use App\Form\AdvertisementType;
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
     * @Route("/", name="back-index")
     * @return Response
     * @throws \Exception
     */
    public function index(): Response
    {
        // get last 3 advertisement not valid !
        $isValid = 0;
        $lastAdvertisement = $this->getDoctrine()->getRepository(Advertisement::class)->findByLastAdvertisement($isValid);

        return $this->render('back/index.html.twig', [
            'advertisements' => $lastAdvertisement
        ]);
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
        // count a users
        $count = $this->getDoctrine()->getRepository(User::class)->findByCount();
        // get all users
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(User::class)->findAll();
        // pagination 9 users in 1 page
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
     * @Route("/annonces/detail-annonces/id={id}", name="back-detail-advertisement", methods="GET|POST")
     * @param int $id
     * @param  Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \Exception
     */
    public function advertisementDetailShow(int $id, Request $request, \Swift_Mailer $mailer): Response {
        //advertisement detail
        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        if($request->isXmlHttpRequest()) {
            // valid advertisement
            $advertisement->setIsvalid(1);
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            // send mail confirmation in the users !
            $user = $advertisement->getUser()->getUsername();
            $email = $advertisement->getUser()->getEmail();

            $message = (new \Swift_Message('Mail de confirmation Le bon point'))
            ->setFrom('annonces@lebonpoint.fr')
            ->setTo($email)
            ->setBody($this->renderView(
                    'emails/advertisement-valid.html.twig', [
                        'advertisement' => $advertisement,
                        'username' => $user
                    ]
                ),
                'text/html'
            )
            ;
            $mailer->send($message);

            return $this->render('back/ajax/advertisement-result.html.twig', [
                'advertisement' => $advertisement
            ]);
        }

        return $this->render('back/advertisement-detail.html.twig', [
            'advertisement' => $advertisement
        ]);
    }

    /**
     * @Route("/annonces/detail-annonce/editer/id={id}", name="back-advertisement-edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function advertisementEditShow(int $id, Request $request): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $advertisement = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
        }

        return $this->render('back/advertisement-edit.html.twig', [
            'form' => $form->createView(),
            'advertisement' => $advertisement
        ]);
    }










}
