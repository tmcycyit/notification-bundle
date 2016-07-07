<?php

namespace Yit\NotificationBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Yit\NotificationBundle\Entity\Notification;
use Yit\NotificationBundle\Entity\NotificationStatus;
use Yit\NotificationBundle\Entity\NotificationType;
use Yit\NotificationBundle\Entity\PreparedNotification;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\PHPExcelPDFExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;



/**
 *
 * Class MainController
 * @package Tmcycyit\NotificationBundle\Controller
 * @Route("notification")
 */
class MainController extends Controller
{
    const ENTITY = 'YitNotificationBundle:NotificationStatus';


    /**
     * This action is used to show all receive notifications of current user
     *
     * @Route("/" , name = "show-receive")
     * @Route("/{code}" , name = "show-receive-with-code")
     * @Template()
     */
    public function showReceiveAction($code = null)
    {
        $user = $this->getUser(); // get current user

        if(!$user) {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        // use grid
        $noteGrid = $this->container->getParameter('yit_notification.note_grid');

        if($noteGrid){

            // adding actions
            $tr = $this->get('translator');

            // Creates a simple grid based on your entity (ORM)
            $source = new Entity('YitNotificationBundle:NotificationStatus');

            // create query
            $entity = $source->getTableAlias();

            $source->manipulateQuery(
                function ($query) use ($entity, $user)
                {
                    $query->andWhere($entity . '.toUser = ' . $user->getId());
                }
            );

            // Get a Grid instance
            $grid = $this->get('grid');

            // add checkbox with delete action
            $grid->addMassAction(new DeleteMassAction());

            $grid->setDefaultOrder('id', 'desc');

            $rowAction = new RowAction($tr->trans('delete', array(), 'note'), 'delete');

            $grid->addRowAction($rowAction);

            // Attach the source to the grid
            $grid->setSource($source);

            // adding exports
            $grid->addExport(new CSVExport('CSV', 'place_list'));

            $grid->addExport(new ExcelExport('Excel', 'place_list'));

            $grid->addExport(new PHPExcelPDFExport('PDF', 'place_list'));


            return $grid->getGridResponse('YitNotificationBundle:Main:showReceive.html.twig',
                array('noteGrid' => $noteGrid));
        }
        else {

            $em = $this->getDoctrine()->getManager();   //get entity manager

            if($code){
                // return all receives notes, or null
                $receives = $em->getRepository(self::ENTITY)->findAllReceiveByUserIdAndCode($user->getId(), $code);

            }
            else{
                // return all receives notes, or null
                $receives = $em->getRepository(self::ENTITY)->findAllReceiveByUserId($user->getId());
            }

            // get pagination
            $paginator  = $this->get('knp_paginator');

            //get count off notes in page
            $per_page = $this->container->getParameter('yit_notification.item_notes_page');

            //number of pages
            $pagination = $paginator->paginate($receives, $this->get('request')->query->get('page', 1), $per_page );

            //get note`s count, and set it in twig global
            $this->getNoteCount();

            $templates = $this->container->getParameter('yit_notification.templates.showReceive'); // get templates name
            return $this->render( $templates, array('receives' => $pagination));
         }
    }


    /**
     * This action is used to show receive of send notifications
     *
     * @Route("/receive-detailed/{notificId}/" , name = "receive-detailed")
     * @Template()
     */
    public function receiveDetailedAction($notificId)
    {
        $em = $this->getDoctrine()->getManager(); //get entity manager

        $user = $this->getUser();  // get current user

        $notification = $em->getRepository(self::ENTITY)->findNotificationById($notificId, $user->getId());


        if (!$notification) //return 404 if notification not found
        {
            throw $this->createNotFoundException("Notification Not Found");
        }

        if($notification->getStatus() == 0) // if notification is unread? (status = 0 unread, status = 1 read)
        {
            $notification->setStatus(1); //set status read
            $em->persist($notification); //update notificationstatus
            $em->flush();
        }

        //get note`s count, and set it in twig global
        $this->getNoteCount();

        $templates = $this->container->getParameter('yit_notification.templates.receiveDetailed'); // get templates name
        return $this->render( $templates, array('notification' => $notification) );
    }

    /**
     * This action is used to show all receive notifications of current user
     *
     * @Route("/delete-all/{userId}" , name = "yit_delete_all")
     */
    public function deleteAllAction($userId)
    {
        // remove notes
        $this->get('yit_note')->removeAllByUser($userId);

        return $this->redirect($this->generateUrl('show-receive'));
    }

    /**
     * This action is used to show all receive notifications of current user
     *
     * @Route("/delete/{id}" , name = "delete")
     */
    public function deleteAction($id)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $note = $em->getRepository('YitNotificationBundle:NotificationStatus')->find($id);
        if($note)
        {
            $em->remove($note);
            $em->flush();
            return $this->redirect($this->generateUrl('show-receive'));
        }
        else
        {
            throw $this->createNotFoundException("Notification Not Found");
        }
    }

    /**
     * This action is used to set read
     *
     * @Route("/set-to-read/{noteId}" , name = "set_to_read")
     *
     * @param $noteId
     * @return Response
     */
    public function setToReadAction($noteId)
    {
        $this->get('yit_note')->setReadToRead($noteId);
        return $this->redirect(
            array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER['HTTP_REFERER'] : 'show-receive');


    }


    /**
     * This function is used to get count of al  received, and unreaedable notification,
     *
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getNoteCount()
    {
        $em = $this->getDoctrine()->getManager(); //get entity manager

        $user = $this->getUser();  // get current user
        if(!$user)
        {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        // get all current user`s recieved notificiation
        $massageCount['allRecieve'] = count($em->getRepository(self::ENTITY)->findAllReceiveByUserId($user->getId()));

        // get all current user`s unreadable notificiation
        $massageCount['unreaduble'] =  $em->getRepository(self::ENTITY)->findAllUnReadableNotificationByUserId($user->getId());


        // set massage count to twig global
        $this->container->get('twig')->addGlobal('noteCount', $massageCount);

    }
}