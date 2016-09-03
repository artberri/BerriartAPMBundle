<?php

namespace Berriart\Bundle\APMBundle\Processor;

use Berriart\Bundle\APMBundle\Processor\ProcessorInterface;

/**
 * Interface that all Processor Handlers must implement
 */
interface ProcessorHandlerInterface
{
    /**
     * Adds a processor in the stack.
     *
     * @param  \Berriart\Bundle\APMBundle\Processor\ProcessorInterface $processor
     * @return self
     */
    public function addProcessor(ProcessorInterface $processor);
}
