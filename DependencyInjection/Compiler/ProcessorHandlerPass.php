<?php

namespace Berriart\Bundle\APMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Berriart\Bundle\APMBundle\Exception\InvalidAPMProcessorException;

class ProcessorHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('berriart_apm.handler')) {
            return;
        }

        $definition = $container->findDefinition('berriart_apm.handler');
        $processors = $container->getParameter('berriart_apm.processors');

        foreach ($processors as $processor) {
            $serviceId = 'berriart_apm.processor.' . $processor;

            if ($container->hasAlias($serviceId) || $container->hasDefinition($serviceId)) {
                $definition->addMethodCall(
                    'addProcessor',
                    array(new Reference($serviceId))
                );
                $processorDefintion = $container->getDefinition($serviceId);
                $processorDefintion->addMethodCall( 'configure', array( $container->getParameter('berriart_apm.config.' . $processor) ) );
            } else {
                throw new InvalidAPMProcessorException(
                    'APM processor not found. Make sure you have configured an APM processor (service) called "' . $serviceId . '" or double check your "berriart_apm" configuration.'
                );
            }
        }
    }
}