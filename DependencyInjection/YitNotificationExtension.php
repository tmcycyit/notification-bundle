<?php

namespace Yit\NotificationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class YitNotificationExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        //get item`s count in page from config
        if(isset($config['item_notes_page']))
        {
            $pageItemCount = $config['item_notes_page']; // if set, get from config
        }
        else
        {
            $pageItemCount = 4; // else set default value
        }

        //get user group from config
        if(isset($config['note_group']))
        {
            $userGroup = $config['note_group']; // if set, get from config
        }
        else
        {
            $userGroup = 'FOS\UserBundle\Entity\Group'; // else set default value
        }

        //get item`s count in page from config
        if(isset($config['item_notes_dropdown']))
        {
            $pageItemNotesCount = $config['item_notes_dropdown']; // if set, get from config
        }
        else
        {
            $pageItemNotesCount = 5; // else set default value
        }


        //insert user
        $container->setParameter($this->getAlias() . '.note_user', $config['note_user']);
        //insert page`s item
        $container->setParameter($this->getAlias() . '.item_notes_page', $pageItemCount);
        //insert page`s item in drop down
        $container->setParameter($this->getAlias() . '.item_notes_dropdown', $pageItemNotesCount);
        //insert user group
        $container->setParameter($this->getAlias() . '.user_group', $userGroup);

        //set tamplate
        $container->setParameter($this->getAlias() . '.templates.receiveDetailed', $config['templates']['receiveDetailed']);
        $container->setParameter($this->getAlias() . '.templates.showReceive', $config['templates']['showReceive']);

        //set admin classes
        $container->setParameter($this->getAlias() . '.admin.note_type', $config['admin']['note_type']);
        $container->setParameter($this->getAlias() . '.admin.prepared_note', $config['admin']['prepared_note']);

    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');

        // Get configuration of our own bundle
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if (isset($bundles['DoctrineBundle'])) //is doctrine bundle set
        {
            //array for resolve target entity
            $insertionForDoctrine = array(
                'orm' => array(
                    'resolve_target_entities' => array(
                        'Yit\NotificationBundle\Model\NoteUserInterface' =>$config['note_user']
                    )
                )
            );

            // insert resolve target entity into config.yml
            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                    case 'doctrine':
                        $container->prependExtensionConfig($name, $insertionForDoctrine);
                        break;
                }
            }
        }

        if (isset($bundles['AsseticBundle'])) //is assetic bundle set
        {
            //array for assetic
            $insertionForAssetic = array('bundles' => array( 'YitNotificationBundle' ));

            // insert assetic bundle nume  into config.yml
            foreach ($container->getExtensions() as $name => $extension)
            {
                switch ($name)
                {
                    case 'assetic':
                        $container->prependExtensionConfig($name, $insertionForAssetic);
                        break;
                }
            }
        }

        if (isset($bundles['TwigBundle'])) //is twig bundle set
        {
            //array for twig
            $insertionForTwig = array(
                'globals' => array('yit_template' =>$config['templates']['layout'] )
            );

            // insert assetic bundle nume  into config.yml
            foreach ($container->getExtensions() as $name => $extension)
            {
                switch ($name)
                {
                    case 'twig':
                        $container->prependExtensionConfig($name, $insertionForTwig);
                        break;
                }
            }
        }
    }
}
