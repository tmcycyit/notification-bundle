<?php

namespace Yit\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Yit\NotificationBundle\Entity\Notification;
use Yit\NotificationBundle\Entity\NotificationStatus;


/**
 *
 * Class MainController
 * @package Yit\NotificationBundle\Controller
 */
class MainController extends Controller
{
    const ENTITY = 'YitNotificationBundle:NotificationStatus';


    /**
     * This action is used to open homepage, ig user is logged or redirect to login page
     *
     * @Route("/" , name = "homepage")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_USER'))
        {
            return $this->redirect($this->generateUrl('dashboard')); //if user logged go to dashboard
        }
        else
        {
            return $this->redirect($this->generateUrl('fos_user_security_login')); // else go to login page
        }
    }

    /**
     *
     *
     * @Route("/dashboard" , name = "dashboard")
     * @Template()
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager(); //get entity manager
        $user = $this->getUser();  // get current user

        // get all current user`s recieved notificiation
        $allRecieve = $em->getRepository(self::ENTITY)->countOfAllReceiveByUserId($user->getId());

        // get all current user`s unreadable notificiation
        $unreaduble =  $em->getRepository(self::ENTITY)->findAllUnReadableNotificationByUserId($user->getId());

        // get all current user`s sended notificiation
        $allSend =  $em->getRepository(self::ENTITY)->countOfAllSendByUserId($user->getId());

        return array('unreaduble' => $unreaduble, 'allRecieve' => $allRecieve, 'allSend' => $allSend);
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

        $fromUser = $this->getUser(); // get current user

        $notification = new Notification();
        $notificationStatus = new NotificationStatus();

        //form for send notification
        $form = $this->createFormBuilder()
            ->add('user', 'entity', array('class' =>'YitUserBundle:User') )
            ->add('header')
            ->add('content', 'textarea')
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

                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
       return array('form' => $form->createView());
    }

    /**
     * This action is used to show all send notifications of current user
     *
     * @Route("/show-send/" , name = "show-send")
     * @Template()
     */
    public function showSendAction()
    {
        $user = $this->getUser(); // get current user
        $em = $this->getDoctrine()->getManager();   //get entity manager
        $sends = $em->getRepository(self::ENTITY)->findAllSendedByUserId($user->getId());
        return array('sends' =>$sends);

    }

    /**
     * This action is used to show all receive notifications of current user
     *
     * @Route("/show-receive/" , name = "show-receive")
     * @Template()
     */
    public function showReceiveAction()
    {
        $user = $this->getUser(); // get current user
        $em = $this->getDoctrine()->getManager();   //get entity manager
        $receives = $em->getRepository(self::ENTITY)->findAllReceiveByUserId($user->getId());
        return array('receives' => $receives);
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

        if($notification->getStatus() == 0) // if notification is unread? (status = 0 unread, status = 1 read)
        {
            $notification->setStatus(1); //set status read
            $em->persist($notification); //update notificationstatus
            $em->flush();
        }
        return array('notification' => $notification);
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
        return array('notification' => $notification);
    }
}