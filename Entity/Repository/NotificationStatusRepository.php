<?php

namespace Yit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class NotificationStatusRepository extends EntityRepository
{

    /**
     * This function is used to find all receive notification by given user`s id
     *
     * @param $userId
     * @return array
     */
    public function findAllReceiveByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns, n, u FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE u = :userid
                           ORDER BY n.created DESC
                          ');
        $query->setParameter('userid' , $userId);
        return $query->getResult();
    }


    /**
     *  This function is used to find all unreadable receive notifications by given user`s id
     *
     * @param $userId
     * @return array
     */
    public function findAllUnReadableNotificationByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT s, u, n FROM YitNotificationBundle:NotificationStatus s
                           LEFT JOIN s.toUser u
                           LEFT JOIN s.notification n
                           WHERE s.toUser = :userid AND s.status = 0
                          ');
        $query->setParameter('userid' , $userId);
        return count($query->getResult());
    }

    /**
     * This function is used to find notification by given notification`s id
     * @param $notificationId
     * @param $userId
     * @return mixed
     */
    public function findNotificationById($notificationId, $userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE n.id = :notificationId and u.id =:userId
                          ');
        $query->setParameter('notificationId' , $notificationId);
        $query->setParameter('userId' , $userId);
        return $query->getSingleResult();
    }

    /**
     *  This function is used to find all sended notifications by given user`s id
     *
     * @param $userId
     * @return array
     */
    public function findAllSendedByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE n.fromUser = :userid
                           ORDER BY n.created DESC
                          ');
        $query->setParameter('userid' , $userId);
        return $query->getResult();
    }


    /**
     * @param $notificationId
     * @param $userId
     * @return mixed|null
     */
    public function findUserNotificationById($notificationId, $userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE n.id = :notificationId AND u.id = :userId
                          ');
        $query->setParameter('notificationId' , $notificationId);
        $query->setParameter('userId' , $userId);

        if(!$query->getResult()) // if query is empty, return null
        {
            return null;
        }
        $query->setMaxResults(1);
        return $query->getSingleResult();
    }

    /**
     * @param $userId
     * @param $count
     * @param $isRead
     * @return array
     */
    public function findReceiveByUserId($userId, $count, $isRead = true)
    {
        $query = $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('ns, n, u')
                        ->from('YitNotificationBundle:NotificationStatus', 'ns')
                        ->leftJoin('ns.toUser', 'u')
                        ->leftJoin('ns.notification', 'n')
                        ->where('u = :userid');

        if ($isRead) {
            $query->andWhere('ns.status = 0');
        }

        $query->orderBy('n.created', 'DESC')
                ->setMaxResults($count)
                ->setParameter('userid' , $userId);

        return $query->getQuery()->getResult();
    }

}