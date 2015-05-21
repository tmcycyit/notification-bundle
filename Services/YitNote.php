<?php

namespace Yit\NotificationBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use  Yit\NotificationBundle\Entity\Notification;
use  Yit\NotificationBundle\Entity\NotificationStatus;
use Yit\NotificationBundle\Entity\PreparedNotification;
use Yit\NotificationBundle\Model\NoteUserInterface;
use Symfony\Component\HttpKernel\Exception\HttpNotFoundException;

class YitNote
{
    protected  $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $recievers
     * @param PreparedNotification $PreparedNotification
     * @param array $arg
     * @param null $userInfo
     */
    public function sendNote( array $recievers, PreparedNotification $PreparedNotification, array $arg = null, $userInfo = null)
    {
        $carrentUser = $this->container->get('security.context')->getToken()->getUser();
        $tr = $this->container->get('translator');

        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $notification = new Notification();
        // set prepared notification
        $notification->setPreparedNotification($PreparedNotification);
        $notification->setFromUser($carrentUser); //set sender
        $notification->setHeader($tr->trans($PreparedNotification->getCode(), array(), 'note'));  //set title
        $notification->setUserInfo($userInfo);
        // if arg is set
        if($arg)
        {
            // get arg and replace it in content
            $content = $PreparedNotification->getReplacedNotification($arg);
            // set new content
            $notification->setContent($content); // set content
        }
        else
        {
            // else read content from preapared notification
            $notification->setContent($PreparedNotification->getContent()); // set content
        }
        foreach($recievers as $reciever)
        {
            if($reciever != $carrentUser)
            {
                $notificationStatus = new NotificationStatus();
                $notificationStatus->setToUser($reciever); //set recievers
                $notificationStatus->setStatus(0); //set status unread
                $notificationStatus->setNotification($notification); // set relations
                $em->persist($notificationStatus); //persist notification status
            }
        }

        $notification->setCreated(new \DateTime('now')); //set notifications date

        $em->persist($notification); //persist status
        $em->flush();
    }

    public function getPreparedNoteByCode($actionCode)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        return $em->getRepository('YitNotificationBundle:PreparedNotification')->findAllByCode($actionCode);
    }


    /**
     * @param $user
     * @return mixed
     */
    public function getNotesCount($user)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        // get all current user`s recieved notificiation
        $massageCount['all'] = count($em->getRepository('YitNotificationBundle:NotificationStatus')->findAllReceiveByUserId($user->getId()));

        // get all current user`s unreadable notificiation
        $massageCount['unreadable'] =  $em->getRepository('YitNotificationBundle:NotificationStatus')->findAllUnReadableNotificationByUserId($user->getId());

        return $massageCount;

    }

    /**
     * @param $id
     * @param $userId
     * @return bool
     */
    public function deleteNote($id, $userId)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $note = $em->getRepository('YitNotificationBundle:NotificationStatus')->findUserNotificationById($id, $userId);
        if($note)
        {
            $em->remove($note);
            $em->flush();
            return true;
        }
        return false;
    }

    /**
     * @param $userId
     * @param $isRead
     * @return mixed
     */
    public function getUserNotes($userId, $isRead = true)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $count = $this->container->getParameter('yit_notification.item_notes_dropdown');

        $receives = $em->getRepository('YitNotificationBundle:NotificationStatus')->findReceiveByUserId($userId, $count, $isRead);

        return $receives;
    }

    /**
     * This function is used to remove older notes
     *
     * @param $month
     */
    public function removeOlder($month)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $em->getRepository('YitNotificationBundle:NotificationStatus')->removeAllOlder($month);
        $em->getRepository('YitNotificationBundle:NotificationStatus')->removeAllUnStatus();
    }


    /**
     * @param $userId
     */
    public function removeAllByUser($userId)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $em->getRepository('YitNotificationBundle:NotificationStatus')->removeAllUserNotes($userId);
        $em->getRepository('YitNotificationBundle:NotificationStatus')->removeAllUnStatus();
    }
}