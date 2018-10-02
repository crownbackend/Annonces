<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Advertisement;
use App\Form\AdvertisementType;
use App\Entity\ReasonOfDealt;
use App\Form\MessagesType;
use App\Form\ReasonOfDealtType;
use App\Form\ShareAdvertisementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Front controller this controller manages all the party front of the website

/**
 * @Route("/{_locale}")
 */

class AdvertisementController extends Controller
{

    /**
     * add new advertisement
     * @Route({"fr": "/annonces/ajouter-une-annonce",
     *         "en": "/advertisement/add-an-advertisement",
     *         "es": "/anuncio/anadir-una-anuncio"}, name="add-advertisement", methods="POST|GET")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function addAdvertisement(Request $request, \Swift_Mailer $mailer): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // treatment and persist new advertisement
            $advertisement = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $advertisement->setUser($user);
            $manager->persist($advertisement);
            $manager->flush();

            //send a confirmation email to the user
            $email = $this->getUser()->getEmail();
            $username = $this->getUser()->getUsername();
            $message = (new \Swift_Message('Mail de confirmation le bon point'))
                ->setFrom('annonces@lebonpoint.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/confirmation.html.twig',
                        [
                            'username' => $username
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('add-advertisement', 'Votre annonce à bien été ajouté, vous aller recevoir un mail de confirmation !');
            return $this->redirectToRoute('my-advertisement');
        }
        return $this->render('advertisement/add-advertisement.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * get category and advertisement
     * @Route({"fr": "/offres/{categorySlug}/{advertisementSlug}",
     *         "en": "/offers/{categorySlug}/{advertisementSlug}",
     *         "es": "/ofertas/{categorySlug}/{advertisementSlug}"}, name="advertisement", methods="GET|POST")
     * @param string $advertisementSlug
     * @param string $categorySlug
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function advertisementShow(string $advertisementSlug, string $categorySlug, Request $request, \Swift_Mailer $mailer): response{
        $isValid = 1;
        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)
            ->findBySlugAdvertisement($advertisementSlug, $categorySlug, $isValid);
        $share = $this->createForm(ShareAdvertisementType::class);
        $share->handleRequest($request);

        if($share->isSubmitted() && $share->isValid()) {
            $ad = $advertisement->getTitle();
            $data = $share->getData();
            $message = (new \Swift_Message('Une annonce pour vous sur le bon point : "'.$ad.'"'))
                ->setFrom($data['from'])
                ->setTo($data['to'])
                ->setBody(
                    $this->renderView(
                        'emails/shareAdvertisement.html.twig',
                        [
                            'advertisement' => $advertisement,
                            'from' => $data['from']
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->render('inc/share-ok.html.twig');

        }

        return $this->render('advertisement/advertisement.html.twig', [
            'advertisement' => $advertisement,
            'form' => $share->createView()
        ]);
    }

    /** mon compte mes annonces
     * get advertisement from the connected user
     * @Route({"fr": "/mon-compte/mes-annonces",
     *         "en": "/my-account/my-advertisement",
     *         "es": "/mi-cuenta/mis-anuncios"}, name="my-advertisement", methods="GET")
     * @return Response
     * @throws \Exception
     */
    public function myAdvertisementShow(): Response {
        // valid or not
        $isValid = 1;
        $notValid = 0;
        //get current users
        $userCurrent = $this->getUser();
        // get advertisement in current users, valid and not valid
        $advertisementsValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByMyAdvertisementValid($userCurrent, $isValid);
        $advertisementsNotValid = $this->getDoctrine()->getRepository(Advertisement::class)->findByMyAdvertisementNotValid($userCurrent, $notValid);
        // count advertisement, valid, not valid
        $countValid = $this->getDoctrine()->getRepository(Advertisement::class)
            ->findByCountMyAdvertisementValid($userCurrent, $isValid); // count advertisement valid
        $countNotValid = $this->getDoctrine()->getRepository(Advertisement::class)
        ->findByCountMyAdvertisementNotValid($userCurrent, $notValid); // count advertisement not Valid

        return $this->render('advertisement/my-advertisement.html.twig', [
            'advertisementsValid' => $advertisementsValid,
            'advertisementsNotValid' => $advertisementsNotValid,
            'countActive' => $countValid,
            'countNotActive' => $countNotValid
        ]);
    }

    /**
     * edit advertisement
     * @Route({"fr": "/mon-compte/mes-annonces/editer/{id}",
     *         "en": "/my-account/my-advertisement/edit/{id}",
     *         "es": "/mi-cuenta/mis-anuncios/editar/{id}"},
     *         name="my-advertisement-edit", methods="GET|POST", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function myAdvertisementEdit(Request $request, int $id, \Swift_Mailer $mailer): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $email = $this->getUser()->getEmail();
            $username = $this->getUser()->getUsername();

            $advertisement = $form->getData();
            // the bool isValid set 0
            $advertisement->setIsvalid(0);
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $this->addFlash('edit-my-advertisement', 'Votre demande de modification à bien été prise 
            en compte il faut compter environs 24h pour se faire valider une 
            annonce déja posté(l\'annonce ne sera pas visible pendant les prochaine 24h !)');
            // send mail in edit advertisement
            $message = (new \Swift_Message('Demande de modification le bon point'))
                ->setFrom('annonces@lebonpoint.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/modification.html.twig',
                        [
                            'username' => $username,
                            'advertisement' => $advertisement
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->redirectToRoute('my-advertisement');
        }
        return $this->render('advertisement/edit-my-advertisement.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * confirm in delete advertisement
     * @Route({"fr": "/mon-compte/mes-annonces/confirmation-suppression/{id}",
     *         "en": "/my-account/my-advertisement/confirmation-delete/{id}",
     *         "es": "/mi-cuenta/mis-anuncios/confirmacion-supresion/{id}"},
     *         name="confirm-delete", methods="GET|POST", requirements={"id"="\d+"})
     * @param int $id
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     */
    public function confirmDelete(int $id, Request $request, \Swift_Mailer $mailer): Response {

        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);

        $reasonOfDealt = new ReasonOfDealt();
        $form = $this->createForm(ReasonOfDealtType::class, $reasonOfDealt);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // presist a message for the cause of deletion
            $reasonOfDealt = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($reasonOfDealt);
            $manager->flush();

            //send a confirmation email to the user
            $email = $this->getUser()->getEmail();
            $username = $this->getUser()->getUsername();
            $message = (new \Swift_Message('Mail de confirmation le bon point'))
                ->setFrom('annonces@lebonpoint.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/delete.html.twig',
                        [
                            'username' => $username,
                            'advertisement' => $advertisement
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->redirectToRoute('delete-advertisement', ['id' => $advertisement->getId()]);
        }

        return $this->render('advertisement/confirm-delete.html.twig', [
            'confirmationDelete' => $advertisement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route({"fr": "/mon-compte/mes-annonces/suppression/{id}",
     *         "en": "/my-account/my-advertisement/delete/{id}",
     *         "es": "/mi-cuenta/mis-anuncios/supresion/{id}"}, name="delete-advertisement", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function deleteAdvertisement(int $id): Response {
        // get current advertisement
        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        // and delete this !
        $delete = $this->getDoctrine()->getManager();
        $delete->remove($advertisement);
        $delete->flush();
        $this->addFlash('delete', 'L\'annonce à bien étais supprimé !');

        return $this->redirectToRoute('my-advertisement');
    }

    /**
     * @Route({"fr": "/annonce/envoyer-message/id={id}",
     *         "en": "/my-account/send-message/id={id}",
     *         "es": "/mi-cuenta/enviar-mensaje/id={id}"}, name="send-a-message", methods="GET|POST", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function sendMessage(int $id, Request $request): Response {
        //get current advertisement
        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        $title = $advertisement->getTitle();
        $to = $advertisement->getUser();
        $from = $this->getUser();

        $message = new Messages();
        $form = $this->createForm(MessagesType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $message = $form->getData();
            $manager = $this->getDoctrine()->getManager();

            $message->setTitle($title);
            $message->setFromId($from);
            $message->setToId($to);
            $message->setAdvertisement($advertisement);
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('my-advertisement');

        }

        return $this->render('advertisement/send-a-message.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route({"fr": "/messages/mes-discussions/id={id}",
     *         "en": "/messages/my-discussions/id={id}",
     *         "es": "/mensajes/mis-discusiones/id={id}"}, name="message")
     * @param int $id

     * @param Request $request
     * @return Response
     * @throws \Exception
     */

    public  function myDiscussions(int $id, Request $request): Response {


        $advertisement = $this->getDoctrine()->getRepository(Advertisement::class)->find($id);
        $title = $advertisement->getTitle();

        $from = $this->getUser();
        $to = $advertisement->getUser();

        $messages = $this->getDoctrine()->getRepository(Advertisement::class)->findByMyMessages($advertisement, $from, $to);

        $message = new Messages();
        $form = $this->createForm(MessagesType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $message = $form->getData();
            $manager = $this->getDoctrine()->getManager();

            $message->setTitle($title);
            $message->setFromId($from);
            $message->setToId($to);
            $message->setAdvertisement($advertisement);
            $manager->persist($message);
            $manager->flush();

            //return $this->redirectToRoute('my-advertisement');

        }

        return $this->render('advertisement/discussions.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form->createView(),
            'messages' => $messages
        ]);
    }






























}
