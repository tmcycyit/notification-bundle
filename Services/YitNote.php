<?php

namespace Yit\NotificationBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use  Yit\NotificationBundle\Entity\Notification;
use  Yit\NotificationBundle\Entity\NotificationStatus;
use Yit\NotificationBundle\Entity\PreparedNotification;
use Yit\NotificationBundle\Model\NoteUserInterface;

class YitNote
{
    protected  $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * This function is used to send notification
     *
     * @param $sender
     * @param array $recievers
     * @param PreparedNotification $PreparedNotification
     * @param array $arguments
     */
    public function sendNote( $sender, array $recievers, PreparedNotification $PreparedNotification, array $arguments = NULL)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $notification = new Notification();
        //$notificationStatus = new NotificationStatus();

        $notification->setFromUser($sender); //set sender
        $notification->setHeader($PreparedNotification->getTitle()); //set title

        if($arguments)
        {
            //if is set argyments, replace it in content
            $PreparedNotification->getReplacedNotification($arguments);
        }
        $notification->setContent($PreparedNotification->getContent()); // set content

        foreach($recievers as $reciever)
        {
            $notificationStatus = new NotificationStatus();
            $notificationStatus->setToUser($reciever); //set recievers
            $notificationStatus->setStatus(0); //set status unread
            $notificationStatus->setNotification($notification); // set relations
            $em->persist($notificationStatus); //persist notification status
        }

        $notification->setCreated(new \DateTime('now')); //set notifications date

        $em->persist($notification); //persist status
        $em->flush();
    }
}