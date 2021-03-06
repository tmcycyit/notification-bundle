<?php

namespace Tmcycyit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PreparedNoteRepository extends EntityRepository
{

    public function findAllByCode($code)
    {

        $query = $this->getEntityManager()
            ->createQuery('SELECT pn, nt FROM TmcycyitNotificationBundle:PreparedNotification pn
                           LEFT JOIN pn.notificationType nt
                           WHERE pn.code = :code
                          ');
        $query->setParameter('code' , $code);
        return $query->getOneOrNullResult();
    }

}