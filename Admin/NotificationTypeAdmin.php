<?php

namespace Tmcycyit\NotificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Tmcycyit\NotificationBundle\Form\Type\FileFieldType;


class NotificationTypeAdmin extends Admin
{

    /**
     * This function used to show entity fields detailed
     * @param ShowMapper $showMapper
     */

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('title');
    }

    /**
     * This function used to show entity field with actions
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
                ->addIdentifier('title')
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
        $formMapper
                ->add('file', 'file', array('required' => false))
                ->add('path', FileFieldType::class, array('required' => false, 'label' => 'path'))
                ->add('title');
    }

    /**
     * This function is used to filter entities
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title');
    }

    /**
     * @param mixed $type
     */
    public function prePersist($type)
    {
        $type->upload();
    }

    /**
     * @param mixed $type
     */
    public function preUpdate($type)
    {
        $type->upload();
    }

    public function getFormTheme() {
        return array('TmcycyitNotificationBundle:Admin:file.html.twig');
    }
}