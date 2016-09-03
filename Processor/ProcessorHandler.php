<?php

namespace Berriart\Bundle\APMBundle\Processor;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Berriart\Bundle\APMBundle\Processor\ProcessorInterface;

class ProcessorHandler implements ProcessorHandlerInterface, ProcessorInterface
{
    private $processors;

    public function __construct()
    {
        $this->processors = array();
    }

    public function configure($config)
    {
    }

    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    public function trackException(\Exception $exception)
    {
        $this->batch('trackException', $exception);

        return $this;
    }

    public function trackRequest(GetResponseEvent $event)
    {
        $this->batch('trackRequest', $event);

        return $this;
    }

    public function trackResponse(PostResponseEvent $event)
    {
        $this->batch('trackResponse', $event);

        return $this;
    }

    protected function batch($method, $argument)
    {
        foreach ($this->processors as $processor) {
            call_user_func(array($processor, $method), $argument);
        }
    }
}
