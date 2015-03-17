<?php

namespace Yit\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('yit_notification');

        $rootNode->children()
            ->scalarNode('note_user')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('note_group')->end()
            ->scalarNode('item_notes_page')->end()
            ->scalarNode('item_notes_dropdown')->end()
            ->booleanNode('note_grid')->end()
            ->arrayNode('admin')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('prepared_note')->defaultValue('Yit\NotificationBundle\Admin\PreparedNotificationAdmin')->cannotBeEmpty()->end()
            ->scalarNode('note_type')->defaultValue('Yit\NotificationBundle\Admin\NotificationTypeAdmin')->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->arrayNode('templates')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('layout')->defaultValue('YitNotificationBundle::noteLayout.html.twig')->cannotBeEmpty()->end()
            ->scalarNode('receiveDetailed')->defaultValue('YitNotificationBundle:Main:receiveDetailed.html.twig')->cannotBeEmpty()->end()
            ->scalarNode('showReceive')->defaultValue('YitNotificationBundle:Main:showReceive.html.twig')->cannotBeEmpty()->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
