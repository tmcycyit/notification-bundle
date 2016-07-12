<?php

namespace Tmcycyit\NotificationBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Tmcycyit\NotificationBundle\Entity\FastNote;
use Tmcycyit\NotificationBundle\Entity\FastNoteStatus;
use  Tmcycyit\NotificationBundle\Entity\Notification;
use  Tmcycyit\NotificationBundle\Entity\NotificationStatus;
use Tmcycyit\NotificationBundle\Entity\PreparedNotification;
use Tmcycyit\NotificationBundle\Model\NoteUserInterface;
use Symfony\Component\HttpKernel\Exception\HttpNotFoundException;

/**
 * Class TmcycyitNote
 * @package Tmcycyit\NotificationBundle\Services
 */
class TmcycyitNote
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected  $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return bool
     */
    public function mustSent()
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        // get user
        $currentUser = $this->container->get('security.context')->getToken()->getUser();

        // get roles
        $roles = $em->getRepository("TmcycyitNotificationBundle:FastPreparedNote")->findRolesByUser($currentUser);

        // check data
        if($roles){
            return true;
        }

        return false;
    }

    /**
     * @param $roles
     * @return array
     */
    public  function getReceivers($roles)
    {
        // empty data for receivers
        $receivers = array();

        // check role
        if($roles){

            // get entity manager
            $em = $this->container->get('doctrine')->getManager();

            // get user repository
            $userRepository = $this->container->getParameter('tmcycyit_notification.note_user');

            // check to user roles
            if($roles){

                // create query builder
                $builder = $em->createQueryBuilder();

                $builder
                    ->select('u')
                    ->from($userRepository, 'u')
                    ->join('u.groups', 'g')
                ;
                foreach($roles as $toUserGroup){

                    $builder
                        ->orWhere("g.roles LIKE '%" . $toUserGroup . "%' ");
                }

                // get receivers
                $receivers =  $builder->getQuery()->getResult();
            }
        }
        return $receivers;
    }


    /**
     * This function is used to sent fast notifications
     *
     * @param $content
     * @param $title
     * @param $receivers
     */
    public function sendFastNote($content, $title, $receivers)
    {
        // get user
        $currentUser = $this->container->get('security.context')->getToken()->getUser();

        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $fastNote = new FastNote();

        // set prepared notification
        $fastNote->setFromUser($currentUser); //set sender
        $fastNote->setTitle($title);  //set title
        $fastNote->setContent($content);  //set content

        // loop for receivers
        foreach($receivers as $receiver)
        {
            // don`t send note himself
            if($receiver != $currentUser) {
                $fastNoteStatus = new FastNoteStatus();

                $fastNoteStatus->setToUser($receiver); //set $receiver
                $fastNoteStatus->setStatus(FastNote::UN_READ); //set status unread
                $fastNote->addNoteStatus($fastNoteStatus);

                $em->persist($fastNoteStatus); //persist notification status
            }
        }

        $fastNote->setCreated(new \DateTime('now')); //set notifications date

        $em->persist($fastNote); //persist status
        $em->flush();
    }

    /**
     *
     * This function is used to sent fast notifications
     *
     * @param $content
     * @param $title
     * @param $receivers
     * @param $fromUser
     * @param $noteType
     * @throws \Throwable
     */
    public function sendFastNoteFromUser($content, $title, $receivers,$fromUser,$noteType)
    {
        // get user
//        $currentUser = $this->container->get('security.context')->getToken()->getUser();

        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $fastNote = new FastNote();
        // set prepared notification
        $fastNote->setFromUser($fromUser); //set sender
        $fastNote->setTitle($title);  //set title
        $fastNote->setContent($content);  //set content
        $fastNote->setNoteType($noteType);

        // loop for receivers
        foreach($receivers as $receiver)
        {
            // don`t send note himself
            if($receiver != $fromUser) {
                $fastNoteStatus = new FastNoteStatus();

                $fastNoteStatus->setToUser($receiver); //set $receiver
                $fastNoteStatus->setStatus(FastNote::UN_READ); //set status unread
                $fastNote->addNoteStatus($fastNoteStatus);

                $em->persist($fastNoteStatus); //persist notification status
            }
        }

        $fastNote->setCreated(new \DateTime('now')); //set notifications date

        $em->persist($fastNote); //persist status
        $em->flush();
    }


    /**
     * @param array $recievers
     * @param PreparedNotification $PreparedNotification
     * @param array $arg
     * @param null $userInfo
     * @param null $header
     */
    public function sendNote( array $recievers, PreparedNotification $PreparedNotification,
                              array $arg = null, $userInfo = null, $header = null)
    {
        $carrentUser = $this->container->get('security.context')->getToken()->getUser();
        $tr = $this->container->get('translator');

        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $notification = new Notification();
        // set prepared notification
        $notification->setPreparedNotification($PreparedNotification);
        $notification->setFromUser($carrentUser); //set sender
        $notification->setHeader($tr->trans($PreparedNotification->getCode(), array(), 'note') . ' ' . $header);  //set title
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

        return $em->getRepository('TmcycyitNotificationBundle:PreparedNotification')->findAllByCode($actionCode);
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
        $massageCount['all'] = count($em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->findAllReceiveByUserId($user->getId()));

        // get all current user`s unreadable notificiation
        $massageCount['unreadable'] =  $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->findAllUnReadableNotificationByUserId($user->getId());

        return $massageCount;

    }


    /**
     * @param $user
     * @return mixed
     */
    public function getNotesCountSortable($user)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        // get all current user`s recieved notificiation
        $massageCount['all'] = $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->findAllReceiveByUserIdWithGroup($user->getId());

        // get all current user`s unreadable notificiation
        $massageCount['unreadable'] =  $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->findAllUnReadableNotificationByUserIdWithGroup($user->getId());

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

        $note = $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->findUserNotificationById($id, $userId);
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

        $count = $this->container->getParameter('tmcycyit_notification.item_notes_dropdown');

        $receives = $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->findReceiveByUserId($userId, $count, $isRead);

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

        $count = $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->removeAllOlder($month);
        $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->removeAllUnStatus();

        return $count;
    }


    /**
     * @param $userId
     */
    public function removeAllByUser($userId)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $count = $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->removeAllUserNotes($userId);
        $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')->removeAllUnStatus();

        return $count;
    }


    /**
     * @param $noteId
     */
    public function setReadToRead($noteId)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        // get  user
        $user = $this->container->get('security.context')->getToken()->getUser();

        if($noteId == -1) {

            $em->createQuery ('Update TmcycyitNotificationBundle:NotificationStatus ns SET ns.status = 1 WHERE ns.toUser = :user')
                ->setParameter('user', $user)->execute();
        }
        else {

            // get notification status by id
            $notification = $em->getRepository('TmcycyitNotificationBundle:NotificationStatus')
                ->findNotificationById($noteId, $user->getId());

            if($notification) {

                $notification->setStatus(1);
                $em->flush();
            }
        }
    }
}