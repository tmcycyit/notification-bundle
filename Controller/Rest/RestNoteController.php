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
        return $this->get('yit_note')->getUserNotes($userId);
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
        $user = $this->container->get('security.context')->getToken()->getUser();

        if($noteId == -1)
        {
            $em->createQuery ('Update YitNotificationBundle:NotificationStatus ns SET ns.status = 1 WHERE ns.toUser = :user')
                ->setParameter('user', $user)->execute();
        }
        else
        {
            // get Last Modified field
            $notification = $em->getRepository('YitNotificationBundle:NotificationStatus')->findNotificationById($noteId, $user->getId());
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