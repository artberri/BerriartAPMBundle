<?php

namespace Berriart\Bundle\APMBundle\EventListener;

use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Berriart\Bundle\APMBundle\Processor\ProcessorHandler;

/**
 * Tracks exceptions on APM
 */
class KernelListener
{
    protected $handler;

    public function __construct(ProcessorHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Handles the onKernelException event.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof HttpExceptionInterface) {
            $this->handler->trackException($exception);
        }
    }

    /**
     * Handles the onKernelResponse event.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {

        if ($this->isTrackableRequest($event)) {
            $this->handler->trackRequest($event);
        }
    }

    /**
     * Handles the onKernelTerminate event.
     *
     * @param \Symfony\Component\HttpKernel\Event\PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        if ($this->isTrackableRequest($event)) {
            $this->handler->trackResponse($event);
        }
    }

    private function isTrackableRequest(KernelEvent $event)
    {
        $uri = $event->getRequest()->getRequestUri();
        if ($event->isMasterRequest() && !preg_match('#^/_profiler|^/_wdt#', $uri)) {
            return true;
        }

        return false;
    }
}
