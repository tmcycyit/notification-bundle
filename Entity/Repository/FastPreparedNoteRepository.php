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
        // get all groups
        $groups = $fromUser->getGroups();
        $code = null;

        // array for user to send note
        foreach($groups as $group) {

            if(method_exists($group, 'getCode')) {
                // set selected value and selected options
                $code = $group->getCode();
            }
            else{
                $roles = $group->getRoles();
                $roles = reset($roles);
                $code = $roles;
            }
        }

        $query = $this->getEntityManager()
            ->createQuery('SELECT pn.toUserGroups FROM YitNotificationBundle:FastPreparedNote pn
                           WHERE pn.formUserGroups = :code
                          ');
        $query->setParameter('code' , $code);

        // get result
        $result = $query->getOneOrNullResult();

        $result =  $result ? $result['toUserGroups'] : null;


        $roles = array();

        if($result){
            foreach($result as $res){
                $roles[$res] = $res;
            }
        }

        return $roles;
    }
}