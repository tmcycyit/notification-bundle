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
 * @package Yit\NotificationBundle\Controller
 * @Route("fast-notification")
 */
class FastNoteController extends Controller
{
    const ENTITY = 'YitNotificationBundle:FastNoteStatus';

    /**
     * @Route("/create" , name = "fast-note-create")
     */
    public function fastNoteCreateAction(Request $request)
    {
        // get current user
        $user = $this->getUser();

        // check user
        if(!$user) {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get roles
        $roles = $em->getRepository("YitNotificationBundle:FastPreparedNote")->findRolesByUser($user);

        // create form
        $form = $this->createFormBuilder()
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('save', 'submit', array('label' => 'Send'))
            ->add('toUserGroups', 'choice', array(
                'label'=> 'To User',
                'choices' => $roles, 'required' => true,
                'expanded' => true,
                'multiple' => true,))
            ->getForm();

        // check request
        if($request->isMethod("POST")){

            // get handle request
            $form->handleRequest($request);

            // check form valid
            if($form->isValid()){

                // get data
                $data = $form->getData();

                // get title
                $title = $data['title'];

                // get content
                $content = $data['content'];

                // get groups
                $toUserGroups = $data['toUserGroups'];

                // get service
                $yitNote = $this->get('yitNote');

                // get receivers
                $receivers = $yitNote->getReceivers($toUserGroups);

                // send note
                $yitNote->sendFastNote($content, $title, $receivers);

                return $this->redirect($this->generateUrl('fast-note-list'));
            }
        }

        return $this->render('YitNotificationBundle:FastNote:fastNoteCreate.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/list" , name = "fast-note-list")
     */
    public function fastNoteListAction()
    {
        // get current user
        $user = $this->getUser();

        // check user
        if(!$user) {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        //get entity manager
        $em = $this->getDoctrine()->getManager();

        // return all receives notes, or null
        $receives = $em->getRepository(self::ENTITY)->findAllReceiveByUserId($user->getId());

        // get pagination
        $paginator  = $this->get('knp_paginator');

        //get count off notes in page
        $per_page = $this->container->getParameter('yit_notification.item_notes_page');

        //number of pages
        $pagination = $paginator->paginate($receives, $this->get('request')->query->get('page', 1), $per_page );

        return $this->render( "YitNotificationBundle:FastNote:fastNoteList.html.twig", array('receives' => $pagination));
    }

    /**
     * @Route("/send-list" , name = "fast-note-list")
     */
    public function fastNoteSendListAction()
    {
        // get current user
        $user = $this->getUser();

        // check user
        if(!$user) {
            throw $this->createNotFoundException("User Not Found, You must authenticate first ");
        }

        //get entity manager
        $em = $this->getDoctrine()->getManager();

        // return all receives notes, or null
        $receives = $em->getRepository(self::ENTITY)->findAllSendedByUserId($user->getId());

        // get pagination
        $paginator  = $this->get('knp_paginator');

        //get count off notes in page
        $per_page = $this->container->getParameter('yit_notification.item_notes_page');

        //number of pages
        $pagination = $paginator->paginate($receives, $this->get('request')->query->get('page', 1), $per_page );

        return $this->render( "YitNotificationBundle:FastNote:fastNoteList.html.twig", array('sending' => $pagination));
    }


}