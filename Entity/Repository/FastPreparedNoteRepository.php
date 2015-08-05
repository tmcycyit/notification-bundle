<?php

namespace Yit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class FastPreparedNoteRepository
 * @package Yit\NotificationBundle\Entity\Repository
 */
class FastPreparedNoteRepository extends EntityRepository
{

    /**
     * @param $fromUser
     * @return mixed
     */
    public function findRolesByUser($fromUser)
    {
        $code = 'ROLE_KINDERGARTEN';

        $query = $this->getEntityManager()
            ->createQuery('SELECT pn.toUserGroups FROM YitNotificationBundle:FastPreparedNote pn
                           WHERE pn.formUserGroups = :code
                          ');
        $query->setParameter('code' , $code);
        return $query->getOneOrNullResult();
    }

}