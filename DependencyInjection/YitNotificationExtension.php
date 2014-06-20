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

        if(isset($config['item_notes_page']))
        {
            $pageItemCount = $config['item_notes_page'];
        }
        else
            $pageItemCount = 4;

        //insert user
        $container->setParameter($this->getAlias() . '.note_user', $config['note_user']);
        //insert page`s item
        $container->setParameter($this->getAlias() . '.item_notes_page', $pageItemCount);

        //set tamplate
        $container->setParameter($this->getAlias() . '.templates.receiveDetailed', $config['templates']['receiveDetailed']);
        $container->setParameter($this->getAlias() . '.templates.sendDetailed', $config['templates']['sendDetailed']);
        $container->setParameter($this->getAlias() . '.templates.showReceive', $config['templates']['showReceive']);
        $container->setParameter($this->getAlias() . '.templates.showSend', $config['templates']['showSend']);
        $container->setParameter($this->getAlias() . '.templates.send', $config['templates']['send']);
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
    }
}
