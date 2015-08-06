<?php

namespace Yit\NotificationBundle\Controller\Rest;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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
        $this->get('yit_note')->setReadToRead($noteId);
        return new Response(Codes::HTTP_OK);

    }


    /**
     * This function is used to to get user groups;
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Note",
     *  description="This function is used to get user groups",
     *  statusCodes={
     *         200="Returned when successful",
     *
     *     }
     * )
     *
     * @Rest\View()
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */

    public function getGroupsAction()
    {
        $em = $this->getDoctrine();

        // get current user
        $user = $this->getUser();

        // get roles
        $roles = $em->getRepository("YitNotificationBundle:FastPreparedNote")->findRolesByUser($user);

        return $roles;
    }
}