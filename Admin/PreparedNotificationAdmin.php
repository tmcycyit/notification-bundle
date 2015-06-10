<?php

namespace Yit\NotificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;



class PreparedNotificationAdmin extends Admin
{

    /**
     * This function used to show entity fields detailed
     * @param ShowMapper $showMapper
     */

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('code')
        ;
    }

    /**
     * This function used to show entity field with actions
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('notificationType')
            ->addIdentifier('code')
            ->add('_action', 'actions', array ('actions' => array (
                'show' => array (),
                'edit' => array (),
                'delete' => array ()
            )));
    }

    /**
     * This function used to add new entity
     *
     * @param FormMapper $formMapper formMapper
     */
    public function configureFormFields(FormMapper $formMapper)
    {
        // get action from config
        $actions = $this->getConfigurationPool()->getContainer()->getParameter('yit_note_actions');
        // get entity manager
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        //get user group
        $userGroup = $this->getConfigurationPool()->getContainer()->getParameter('yit_notification.user_group');

        // get all groups
        $groups = $em->getRepository($userGroup)->findAll();

        // array for user to send note
        $toUsers = array();
        foreach($groups as $group)
        {
            if(method_exists($group, 'getCode')) {
                // set selected value and selected options
                $toUsers[$group->getCode()] = $group->getName();
            }
            else{
                $roles = $group->getRoles();
                $roles = reset($roles);
                $toUsers[$roles] = $group->getName();
            }
        }

        $codes = array();
        foreach($actions as $action)
        {
            $codes[$action] = $this->trans($action, array(), 'note');
        }
        $formMapper
            ->add('notificationType', null, array(
                'label'=> $this->trans('note_type', array(), 'note'),
                'required' => true,
                'empty_value' => $this->trans('choose_type', array(), 'note'),
                'empty_data'  => null
                ))
            ->add('code', 'choice', array(
                'label'=> $this->trans('note_code', array(), 'note'),
                'choices' =>$codes,
                'required' => true,
                'empty_value' => $this->trans('choose_code', array(), 'note'),
                'empty_data'  => null
            ))
            ->add('userGroups', 'choice', array(
                'label'=> $this->trans('not_to_user', array(), 'note'),
                'choices' =>$toUsers, 'required' => true,
                'expanded' => true,
                'multiple' => true,
            ))
        ;
    }

    /**
     * This function is used to filter entities
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('notificationType')
            ->add('code')
            ;
    }
}