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

        if (isset($config['processors'])) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.yml');

            $processors = array();

            foreach ($config['processors'] as $name => $processor) {
                $processors[$processor['priority']][] = $name;
                $container->setParameter('berriart_apm.config.'.$name, $processor);
            }

            ksort($processors);
            $sortedProcessors = array();
            foreach ($processors as $priorityProcessors) {
                foreach (array_reverse($priorityProcessors) as $processor) {
                    $sortedProcessors[] = $processor;
                }
            }

            $container->setParameter('berriart_apm.processors', $sortedProcessors);
        }
    }
}
