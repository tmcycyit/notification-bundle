<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 5/21/15
 * Time: 2:27 PM
 */

namespace Tmcycyit\NotificationBundle\Listener;

use Ads\MainBundle\Entity\Ad;
use Ads\MainBundle\Entity\AdPart;
use Doctrine\Common\Persistence\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Tmcycyit\NotificationBundle\Entity\NotificationStatus;


class NoteListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function postPersist($args)
    {
        // get entity
        $entity = $args->getEntity();

        // check entity
        if($entity instanceof NotificationStatus) {

            // get entity manager
            $em = $args->getEntityManager();

            // get period
            $period = $em->getRepository('TmcycyitNotificationBundle:HistoryPeriod')->findAll();

            // check period
            if(count($period ) > 0){

                $this->container->get('yitnote')->removeOlder($period[0]->getPeriod());
            }
        }
    }
}