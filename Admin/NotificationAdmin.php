<?php

namespace Tmcycyit\NotificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;



class NotificationAdmin extends Admin
{

    /**
     * This function used to show entity fields detailed
     * @param ShowMapper $showMapper
     */

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('header')
                ->add('fromUser')
                ->add('created');
    }

    /**
     * This function used to show entity field with actions
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
                ->addIdentifier('header')
                ->addIdentifier('fromUser')
                ->addIdentifier('created')
                ->add('_action', 'actions', array ('actions' => array (
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
            ->add('header')
            ->add('fromUser')
            ->add('created');
    }

    /**
     * This function is used to filter entities
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('header')
            ->add('fromUser')
            ->add('created','doctrine_orm_datetime_range', array(),
                'sonata_type_date_range',
                array(
                    'required' => false,
                    'label' => 'single_text',
                    'attr' => array(
                        'class' => 'datepicker',
                        'data-date-format' => 'YYYY-MM-DD'
                    )
                )
            );
    }

}