<?php

namespace Tmcycyit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Tmcycyit\NotificationBundle\Entity\FastNote;

class FastNotificationStatusRepository extends EntityRepository
{

    /**
     *
     * This function is used to find all receive notification by given user`s id
     *
     * @param $userId
     * @param int $noteType
     * @return array
     */
    public function findAllReceiveByUserId($userId,$noteType = 0)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns, n, u FROM TmcycyitNotificationBundle:FastNoteStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.fastNote n
                           WHERE u = :userid AND n.noteType = :noteType
                           ORDER BY n.created DESC
                          ');
        $query->setParameter('userid' , $userId);
        $query->setParameter('noteType' , $noteType);
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
            ->createQuery('SELECT n, u FROM TmcycyitNotificationBundle:FastNote n
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
            ->createQuery('SELECT ns, n, u FROM TmcycyitNotificationBundle:FastNoteStatus ns
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