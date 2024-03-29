<?php

namespace Tmcycyit\NotificationBundle\Entity\Repository;

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
            ->createQuery('SELECT ns, n, u FROM TmcycyitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           WHERE u = :userid
                           ORDER BY n.created DESC
                          ');
        $query->setParameter('userid' , $userId);
        return $query->getResult();
    }


    /**
     * This function is used to find all receive notification by given user`s id and code
     *
     * @param $code
     * @param $userId
     * @return array
     */
    public function findAllReceiveByUserIdAndCode($userId, $code)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ns, n, u FROM TmcycyitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           LEFT JOIN n.preparedNotification pn
                           WHERE u = :userid and pn.code = :code
                           ORDER BY n.created DESC
                          ');
        $query->setParameter('userid' , $userId);
        $query->setParameter('code' , $code);
        return $query->getResult();
    }


    /**
     * This function is used to find all receive notification by given user`s id
     *
     * @param $userId
     * @return array
     */
    public function findAllReceiveByUserIdWithGroup($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT COUNT (ns) as cnt, pn.code as code FROM TmcycyitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           LEFT JOIN ns.notification n
                           LEFT JOIN n.preparedNotification pn
                           WHERE u = :userid
                           GROUP BY pn
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
            ->createQuery('SELECT count (s) FROM TmcycyitNotificationBundle:NotificationStatus s
                           INNER JOIN s.toUser u
                           WHERE s.toUser = :userid AND s.status = 0
                          ');
        $query->setParameter('userid' , $userId);
        return $query->getSingleScalarResult();
    }

    /**
     *  This function is used to find all unreadable receive notifications by given user`s id
     *
     * @param $userId
     * @return array
     */
    public function findAllUnReadableNotificationByUserIdWithGroup($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT COUNT (s) as cnt, pn.code as code FROM TmcycyitNotificationBundle:NotificationStatus s
                           LEFT JOIN s.toUser u
                           LEFT JOIN s.notification n
                           LEFT JOIN n.preparedNotification pn
                           WHERE s.toUser = :userid AND s.status = 0
                          GROUP BY pn
                          ');
        $query->setParameter('userid' , $userId);
        return $query->getResult();
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
            ->createQuery('SELECT ns FROM TmcycyitNotificationBundle:NotificationStatus ns
                           LEFT JOIN ns.toUser u
                           WHERE ns.notification = :notificationId and u.id =:userId
                          ');
        $query->setParameter('notificationId' , $notificationId);
        $query->setParameter('userId' , $userId);
        return $query->getOneOrNullResult();
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
            ->createQuery('SELECT ns FROM TmcycyitNotificationBundle:NotificationStatus ns
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
            ->createQuery('SELECT ns FROM TmcycyitNotificationBundle:NotificationStatus ns
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
                        ->select('ns')
                        ->from('TmcycyitNotificationBundle:NotificationStatus', 'ns')
                        ->innerJoin('ns.toUser', 'u')
                        ->innerJoin('ns.notification', 'n')
                        ->where('u.id = :userId');

        if ($isRead) {
            $query->andWhere('ns.status = 0');
        }

        $query->orderBy('n.created', 'DESC')
                ->setMaxResults($count)
                ->setParameter('userId' , $userId);

        return $query->getQuery()->getResult();
    }


    /**
     * @param $month
     * @return bool
     */
    public function removeAllOlder($month)
    {
        $interval = new \DateTime('now');
        $interval->modify("-$month day");

        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('ns.id')
            ->from('TmcycyitNotificationBundle:NotificationStatus', 'ns')
            ->leftJoin('ns.notification', 'n')
            ->where('n.created <=  :interval')
            ->setParameter('interval', $interval->format('Y-m-d'))
        ;


        $ids =  array_map('current', $query->getQuery()->getResult());

        if($ids){

            $this->getEntityManager()
                ->createQueryBuilder()
                ->delete('TmcycyitNotificationBundle:NotificationStatus', 'ns')
                ->where('ns.id in (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()->execute();

        }
        ;


        return $ids;
    }

    /**
     * @return array
     */
    public function removeAllUnStatus()
    {

        // get all ids
        $ids = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.id')
            ->from('TmcycyitNotificationBundle:Notification', 'n')
            ->leftJoin('n.notificationStatus', 'ns')
            ->where('ns is null')->getQuery()->getResult();
        ;

        if($ids){

            $this->getEntityManager()
                ->createQueryBuilder()
                ->delete('TmcycyitNotificationBundle:Notification', 'n')
                ->where('n.id in (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()->execute();
            ;

        }

        return $ids;
    }

    /**
     * @return array
     */
    public function removeCodeAllUnStatus($code)
    {

        // get all ids
        $ids = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.id')
            ->from('TmcycyitNotificationBundle:Notification', 'n')
            ->leftJoin('n.notificationStatus', 'ns')
            ->join('n.preparedNotification','pn')
            ->where('ns is null and pn.code = :code')
            ->setParameter('code',$code)
            ->getQuery()->getResult();
        ;

        if($ids){

            $this->getEntityManager()
                ->createQueryBuilder()
                ->delete('TmcycyitNotificationBundle:Notification', 'n')
                ->where('n.id in (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()->execute();
            ;

        }

        return $ids;
    }
    

    /**
     * @param $userId
     * @return bool
     */
    public function removeAllUserNotes($userId)
    {

        // get all ids
        $ids = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.id')
            ->from('TmcycyitNotificationBundle:NotificationStatus', 'n')
            ->leftJoin('n.toUser', 'ns')
            ->where('ns .id = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
        ;

        if($ids){

            $this->getEntityManager()
                ->createQueryBuilder()
                ->delete('TmcycyitNotificationBundle:NotificationStatus', 'n')
                ->where('n.id in (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()->execute();
            ;

        }

        return $ids;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function removeCodeAllUserNotes($userId,$code)
    {
        // get all ids
        $ids = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.id')
            ->from('TmcycyitNotificationBundle:NotificationStatus', 'n')
            ->leftJoin('n.toUser', 'ns')
            ->join('n.notification','nt')
            ->join('nt.preparedNotification','pn')
            ->where('ns .id = :user and pn.code = :code')
            ->setParameter('user', $userId)
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult();
        ;

        if($ids){

            $this->getEntityManager()
                ->createQueryBuilder()
                ->delete('TmcycyitNotificationBundle:NotificationStatus', 'n')
                ->where('n.id in (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()->execute();
            ;

        }

        return $ids;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function updateCodeAllUserNotes($userId,$code)
    {
        // get all ids
        $ids = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.id')
            ->from('TmcycyitNotificationBundle:NotificationStatus', 'n')
            ->leftJoin('n.toUser', 'ns')
            ->join('n.notification','nt')
            ->join('nt.preparedNotification','pn')
            ->where('ns .id = :user and pn.code = :code')
            ->setParameter('user', $userId)
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult();
        ;

        if($ids){

            $this->getEntityManager()
                ->createQueryBuilder()
                ->update('TmcycyitNotificationBundle:NotificationStatus', 'n')
                ->set('n.status',':status')
                ->where('n.id in (:ids)')
                ->setParameter('ids', $ids)
                ->setParameter('status',1)
                ->getQuery()->execute();
            ;

        }

        return $ids;
    }


}
