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
     * This function is used to get all Notification ;
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Note",
     *  description="This function is used to get all Notification",
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

        //var_dump($receives); exit;

        return $receives;
    }

    /**
     * This function is used to get count of unreadable notifications ;
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Note",
     *  description="This function is used to get count of unreadable notifications",
     *  statusCodes={
     *         200="Returned when successful",
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

        return array("count"=>$count);
    }

    /**
     * This function is used to set notification readable;
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Note",
     *  description="This function is used to set notification readable",
     *  statusCodes={
     *         200="Returned when successful",
     *
     *     }
     * )
     *
     * @param $noteId
     * @Rest\View()
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */

    public function patchReadAction($noteId)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get  user
        $userId = $this->container->get('security.context')->getToken()->getUser()->getId();

        if($noteId == -1)
        {

            $notifications = $em->getRepository('YitNotificationBundle:NotificationStatus')->findAllReceiveByUserId($userId);
            if(!$notifications)
            {
                throw new HttpException (Codes::HTTP_NOT_FOUND);
            }
            foreach($notifications as $note)
            {
                $note->setStatus(1);
                $em->flush();
            }
        }
        else
        {
            // get Last Modified field
            $notification = $em->getRepository('YitNotificationBundle:NotificationStatus')->findNotificationById($noteId, $userId);
            if($notification)
            {
                $notification->setStatus(1);
                $em->flush();
            }
            else
            {
                throw new HttpException (Codes::HTTP_NOT_FOUND);
            }
        }

    }


}