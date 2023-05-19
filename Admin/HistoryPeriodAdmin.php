<?php

namespace Tmcycyit\NotificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;


class HistoryPeriodAdmin extends Admin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        // get entity manager
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        // find all periods
        $periods = $em->getRepository('TmcycyitNotificationBundle:HistoryPeriod')->findAll();

        // remove create button, if there is one periods
        if(count($periods) >= 1)
        {
            $collection
                ->remove('create');
        }
    }

    /**
     * This function used to show entity fields detailed
     * @param ShowMapper $showMapper
     */

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('period');
    }

    /**
     * This function used to show entity field with actions
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
                ->addIdentifier('period')
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
                ->add('period');
    }

    /**
     * This function is used to filter entities
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('period');
    }

    public function executeCommand($object)
    {

        $period = $object->getPeriod();

        if($period > 0){

            // get container
            $container = $this->getConfigurationPool()->getContainer();

            // get kernel root dir
            $kernelRootDir = $container->get('kernel')->getRootDir();

            // create command
            $command = $kernelRootDir . "/console  yitNote:remove:old " . $period;

            // exec
            exec($command, $output, $return);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $this->executeCommand($object);

    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $this->executeCommand($object);
    }




}