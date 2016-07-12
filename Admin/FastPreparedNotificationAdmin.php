<?php

namespace Tmcycyit\NotificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;



class FastPreparedNotificationAdmin extends Admin
{

    /**
     * This function used to show entity fields detailed
     * @param ShowMapper $showMapper
     */

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('formUserGroups')
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
            ->addIdentifier('formUserGroups')
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
        // get entity manager
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        //get user group
        $userGroup = $this->getConfigurationPool()->getContainer()->getParameter('tmcycyit_notification.user_group');

        // get all groups
        $groups = $em->getRepository($userGroup)->findAll();

        // array for user to send note
        $toUsers = array();
        $fromUsers = array();
        foreach($groups as $group)
        {
            if(method_exists($group, 'getCode')) {
                // set selected value and selected options
                $toUsers[$group->getCode()] = $group->getName();
                $fromUsers[$group->getCode()] = $group->getName();
            }
            else{
                $roles = $group->getRoles();
                $roles = reset($roles);
                $toUsers[$roles] = $group->getName();
                $fromUsers[$roles] = $group->getName();
            }
        }


        $formMapper
            ->add('formUserGroups', 'choice', array(
                'label'=> $this->trans('not_from_user', array(), 'note'),
                'choices' => $toUsers, 'required' => true,
            ))
            ->add('toUserGroups', 'choice', array(
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
            ->add('formUserGroups')
            ;
    }
}