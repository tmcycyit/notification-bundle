<?php

namespace Tmcycyit\NotificationBundle\Controller\Rest;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Tmcycyit\NotificationBundle\Entity\FastNote;

/**
 * @package namespace Tmcycyit\NotificationBundle\Controller\Rest
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
     * @Rest\View(serializerGroups={"fast-new"})
     * @Rest\Get("/notes/new/{userId}")
     */
    public function getNewAction($userId)
    {
        // get doctrine
        $em = $this->getDoctrine()->getManager();

        // get all notes
        $notes = $em->getRepository("YitNotificationBundle:FastNoteStatus")->findAllNewByUserId($userId);

        // check notes
        if($notes){

            // loop for all notes
            foreach($notes as $note){

                // set status
                $note->setStatus(FastNote::READ);

                // persist
                $em->persist($note);
            }

            // flush data
            $em->flush();
        }
        return $notes;
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
     * @Rest\View()
     * @return mixed
     *
     * @param Request $request
     * @return Response
     */
    public function postFastSendAction(Request $request)
    {
        // get all data
        $data = $request->request->all();

        $roles = array();

        // check and get title
        if(array_key_exists('messageTitle', $data)){
            $title = $data['messageTitle'];
        }
        else{
            return new Response(Codes::HTTP_NOT_FOUND, 'Title not found');
        }

        // check and get content
        if(array_key_exists('messageContent', $data)){
            $content = $data['messageContent'];
        }
        else{
            return new Response(Codes::HTTP_NOT_FOUND, 'Content not found');
        }

        // check and get selectedRoles
        if(array_key_exists('selectedRoles', $data)){

            $selectedRoles = $data['selectedRoles'];
            if($selectedRoles){
                foreach($selectedRoles as $selectedRole){
                    $roles[$selectedRole]  = $selectedRole;
                }
            }
        }
        else{
            return new Response(Codes::HTTP_NOT_FOUND, 'Roles not found');
        }

        // get note service
        $noteService = $this->get('yitNote');

        // get receivers by role
        $receivers = $noteService->getReceivers($roles);

        // send note
        $noteService->sendFastNote($content, $title, $receivers);

        // return response
        return new Response(Codes::HTTP_OK);

    }
}