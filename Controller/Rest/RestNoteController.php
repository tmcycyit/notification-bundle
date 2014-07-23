<?php

namespace Yit\NotificationBundle\Controller\Rest;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sonata\MediaBundle\Entity\MediaManager;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;

/**
 * @package namespace Yit\NotificationBundle\Controller\Rest
 *
 * @Rest\RouteResource("note")
 * @Rest\Prefix("/yit")
 * @Rest\NamePrefix("rest_")
 */
class RestNoteController extends FOSRestController
{
    /**
     * This function is used to get all Main`s entities ;
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Main",
     *  description="This function is used to get Main`s Entity ",
     *  statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @param $userId
     * @Rest\View(serializerGroups={"list"})
     * @return mixed
     */

    public function cgetAction($userId)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        $count = $this->container->getParameter('yit_notification.item_notes_dropdown');

        $receives = $em->getRepository('YitNotificationBundle:NotificationStatus')->findReceiveByUserId($userId, $count);

        return $receives;
    }

    /**
     * This function is used to get all Main`s entities ;
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Main",
     *  description="This function is used to get Main`s Entity ",
     *  statusCodes={
     *         200="Returned when successful",
     *         304="Returned when date is not modified",
     *     }
     * )
     *
     * @param $userId
     * @Rest\View()
     * @return mixed
     */

    public function getCountAction($userId)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get Last Modified field
        $count = $em->getRepository('YitNotificationBundle:NotificationStatus')->findAllUnReadableNotificationByUserId($userId);

        return $count;
    }


}