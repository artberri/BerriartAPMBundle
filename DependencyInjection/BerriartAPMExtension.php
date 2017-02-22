<?php

namespace Berriart\Bundle\APMBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BerriartAPMExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['services'])) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('apm.yml');

            $clients = [];

            foreach ($config['services'] as $name => $client) {
                $clients[$client['priority']][] = $name;
                $container->setParameter('berriart_apm.config.'.$name, $client);
            }

            ksort($clients);
            $sortedClients = [];
            foreach ($clients as $priorityClients) {
                foreach (array_reverse($priorityClients) as $client) {
                    $sortedClients[] = $client;
                }
            }

            $container->setParameter('berriart_apm.clients', $sortedClients);
            $container->setParameter('berriart_apm.service.alias', $config['alias']);
        }

        $container->setParameter('berriart_apm.listener.rules', $config['listeners']);
    }
}
