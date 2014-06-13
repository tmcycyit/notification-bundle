<?php

namespace Yit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class NotificationStatusRepository extends EntityRepository
{


    /**
     * @param $userId
     * @return int
     */
    public function countOfAllReceiveByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE u = :userid
                          ');
        $query->setParameter('userid' , $userId);
        return count($query->getResult());
    }

    /**
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
     * @param $notificationId
     * @return array
     */
    public function findNotificationById($notificationId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE n.id = :notificationId
                          ');
        $query->setParameter('notificationId' , $notificationId);
        return $query->getSingleResult();
    }

    /**
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
     * @param $userId
     * @return int
     */
    public function countOfAllSendByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns FROM YitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE n.fromUser = :userid
                          ');
        $query->setParameter('userid' , $userId);
        return count($query->getResult());
    }


}