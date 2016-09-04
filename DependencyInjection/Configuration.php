<?php

namespace Berriart\Bundle\APMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('berriart_apm');

        $rootNode
            ->fixXmlConfig('service')
            ->children()
                ->scalarNode('alias')->defaultValue('berriart_apm')->end()
                ->arrayNode('services')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->canBeUnset()
                        ->children()
                            ->scalarNode('api_key')->end()
                            ->scalarNode('priority')->defaultValue(0)->end()
                            ->arrayNode('listeners')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->booleanNode('exceptions')->defaultTrue()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
