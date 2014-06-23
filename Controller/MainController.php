<?php

namespace Yit\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Yit\NotificationBundle\Entity\Notification;
use Yit\NotificationBundle\Entity\NotificationStatus;
use Yit\NotificationBundle\Entity\NotificationType;
use Yit\NotificationBundle\Entity\PreparedNotification;


/**
 *
 * Class MainController
 * @package Yit\NotificationBundle\Controller
 * @Route("notification")
 */
class MainController extends Controller
{
    const ENTITY = 'YitNotificationBundle:NotificationStatus';


    /**
     * This action is used to show all receive notifications of current user
     *
     * @Route("/" , name = "show-receive")
     * @Template()
     */
    public function showReceiveAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_USER'))
        {
            $user = $this->getUser(); // get current user
            if(!$user)
            {
                throw $this->createNotFoundException("User Not Found, You must authenticate first ");
            }

            $em = $this->getDoctrine()->getManager();   //get entity manager

            $receives = $em->getRepository(self::ENTITY)->findAllReceiveByUserId($user->getId());
            if (!$receives) //return 404 if notification not found
            {
                throw $this->createNotFoundException("receive notification Not Found");
            }

            // get pagination
            $paginator  = $this->get('knp_paginator');

            //get count off notes in page
            $per_page = $this->container->getParameter('yit_notification.item_notes_page');

            //number of pages
            $pagination = $paginator->paginate($receives, $this->get('request')->query->get('page', 1), $per_page );

            //get note`s count, and set it in twig global
            $this->getNoteCount();

            $templates = $this->container->getParameter('yit_notification.templates.showReceive'); // get templates name
            return $this->render( $templates, array('receives' => $pagination) );
        }
        else
        {
            return $this->redirect($this->generateUrl('fos_user_security_login')); // else go to login page
        }
    }


    /**
     * This action is used to send notifications
     *
     * @Route("/send/" , name = "send")
     * @Template()
     */
    public function sendAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager(); //get entity manager

        $userClassName = $this->container->getParameter('yit_notification.note_user'); // get user className

        $fromUser = $this->getUser(); // get current user
        if(!$fromUser)
        {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        $notification = new Notification();
        $notificationStatus = new NotificationStatus();

        //form for send notification
        $form = $this->createFormBuilder()
            ->add('user', 'entity', array('label'=>'To', 'class' =>$userClassName) )
            ->add('header', null, array('label'=>'Subject'))
            ->add('content', 'textarea', array('label'=>'Content'))
            ->add('send', 'submit')
            ->getForm();

        if ($request->getMethod() == 'POST') //check method type
        {
            $form->submit($request); // if method type is post, submit form

            if ($form->isValid()) //  check forms data
            {
                //if form is valid get data from form, and insert to entity

                $notification->setHeader($form->get('header')->getData());
                $notification->setContent($form->get('content')->getData());
                $notification->setFromUser($fromUser);
                $notification->setCreated(new \DateTime('now')); //set notifications date

                // by default, set all notification`s status to unread
                $notificationStatus->setNotification($notification);
                $notificationStatus->setToUser(($form->get('user')->getData()));
                $notificationStatus->setStatus(0); // 0 unread, 1 read

                $em->persist($notification);
                $em->persist($notificationStatus);
                $em->flush();

                return $this->redirect($this->generateUrl('show-receive'));
            }
        }

        //get note`s count, and set it in twig global
        $this->getNoteCount();

        $templates = $this->container->getParameter('yit_notification.templates.send'); // get templates name
        return $this->render( $templates, array('form' => $form->createView()) );

    }

    /**
     * This action is used to show all send notifications of current user
     *
     * @Route("/show-send/" , name = "show-send")
     * @Template()
     */
    public function showSendAction(Request $request)
    {
        $user = $this->getUser(); // get current user
        if(!$user)
        {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        $em = $this->getDoctrine()->getManager();   //get entity manager

        $sends = $em->getRepository(self::ENTITY)->findAllSendedByUserId($user->getId());
        if (!$sends) //return 404 if notification not found
        {
            throw $this->createNotFoundException("send notification Not Found");
        }

        // get pagination
        $paginator  = $this->get('knp_paginator');

        //get count off notes in page
        $per_page = $this->container->getParameter('yit_notification.item_notes_page');

        //number of pages
        $pagination = $paginator->paginate($sends, $this->get('request')->query->get('page', 1), $per_page );

        // get templates name
        $templates = $this->container->getParameter('yit_notification.templates.showSend');

        //get note`s count, and set it in twig global
        $this->getNoteCount();

        return $this->render( $templates, array('sends' =>$pagination ) );

    }

    /**
     * This action is used to show receive of send notifications
     *
     * @Route("/receive-detailed/{notificId}/" , name = "receive-detailed")
     * @Template()
     */
    public function receiveDetailedAction($notificId)
    {
        $em = $this->getDoctrine()->getManager(); //get entity manager

        $notification = $em->getRepository(self::ENTITY)->findNotificationById($notificId);
        if (!$notification) //return 404 if notification not found
        {
            throw $this->createNotFoundException("Notification Not Found");
        }

        if($notification->getStatus() == 0) // if notification is unread? (status = 0 unread, status = 1 read)
        {
            $notification->setStatus(1); //set status read
            $em->persist($notification); //update notificationstatus
            $em->flush();
        }

        //get note`s count, and set it in twig global
        $this->getNoteCount();

        $templates = $this->container->getParameter('yit_notification.templates.receiveDetailed'); // get templates name
        return $this->render( $templates, array('notification' => $notification) );
    }

    /**
     *  This action is used to show detailed of send notifications
     *
     * @Route("/send-detailed/{notificId}/" , name = "send-detailed")
     * @Template()
     */
    public function sendDetailedAction($notificId)
    {
        $em = $this->getDoctrine()->getManager(); //get entity manager

        $notification = $em->getRepository(self::ENTITY)->findNotificationById($notificId);
        if (!$notification) //return 404 if notification not found
        {
            throw $this->createNotFoundException("Notification Not Found");
        }

        //get note`s count, and set it in twig global
        $this->getNoteCount();


        $templates = $this->container->getParameter('yit_notification.templates.sendDetailed'); // get templates name
        return $this->render( $templates, array('notification' => $notification) );
    }

    /**
     * This function is used to get count of al sended, received, and unreaedable notification,
     *      is compose added, and set it in twig global
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getNoteCount()
    {
        $em = $this->getDoctrine()->getManager(); //get entity manager

        $user = $this->getUser();  // get current user
        if(!$user)
        {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        // get all current user`s recieved notificiation
        $massageCount['allRecieve'] = count($em->getRepository(self::ENTITY)->findAllReceiveByUserId($user->getId()));

        // get all current user`s unreadable notificiation
        $massageCount['unreaduble'] =  $em->getRepository(self::ENTITY)->findAllUnReadableNotificationByUserId($user->getId());

        // get all current user`s sended notificiation
        $massageCount['allSend'] =  count($em->getRepository(self::ENTITY)->findAllSendedByUserId($user->getId()));

        $compose = $this->container->getParameter('yit_notification.add_compose');

        // set massage count to twig global
        $this->container->get('twig')->addGlobal('noteCount', $massageCount);
        //set compose to twig global
        $this->container->get('twig')->addGlobal('compose' , $compose);
    }
}