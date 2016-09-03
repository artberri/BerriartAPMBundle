<?php

namespace Berriart\Bundle\APMBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Berriart\Bundle\APMBundle\DependencyInjection\Compiler\ProcessorHandlerPass;

class BerriartAPMBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ProcessorHandlerPass());
    }
}
