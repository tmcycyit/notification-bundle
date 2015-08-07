<?php

namespace Yit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Yit\NotificationBundle\Entity\FastNote;

class FastNotificationStatusRepository extends EntityRepository
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
            ->createQuery('SELECT ns, n, u FROM YitNotificationBundle:FastNoteStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.fastNote n
                           WHERE u = :userid
                           ORDER BY n.created DESC
                          ');
        $query->setParameter('userid' , $userId);
        return $query->getResult();
    }


    /**
     * This function is used to find all receive notification by given user`s id
     *
     * @param $userId
     * @return array
     */
    public function findAllSendedByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT n, u FROM YitNotificationBundle:FastNote n
                           LEFT JOIN n.fromUser u
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
    public function findAllNewByUserId($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns, n, u FROM YitNotificationBundle:FastNoteStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.fastNote n
                           WHERE u.id = :userid and ns.status = :status
                           ORDER BY n.created DESC
                          ')
            ->setParameter('userid' , $userId)
            ->setParameter('status' , FastNote::UN_READ)
        ;

        return $query->getResult();
    }
}