<?php

namespace Berriart\Bundle\APMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Berriart\Bundle\APMBundle\Exception\InvalidAPMClientException;

class ClientHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('berriart_apm.handler')) {
            return;
        }

        $definition = $container->findDefinition('berriart_apm.handler');
        $clients = $container->getParameter('berriart_apm.clients');

        foreach ($clients as $client) {
            $serviceId = 'berriart_apm.client.'.$client;

            if (!$container->hasAlias($serviceId) && !$container->hasDefinition($serviceId)) {
                throw new InvalidAPMClientException(
                    'APM client not found. Make sure you have configured an APM client (service) called "'.$serviceId.'" or double check your "berriart_apm" configuration.'
                );
            }

            $definition->addMethodCall(
                'addClient',
                array(new Reference($serviceId), $client)
            );
            $clientDefintion = $container->getDefinition($serviceId);
            $clientDefintion->addMethodCall('configure', array( $container->getParameter('berriart_apm.config.'.$client)));
        }
    }
}
