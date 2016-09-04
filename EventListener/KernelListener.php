<?php

namespace Berriart\Bundle\APMBundle\EventListener;

use AppKernel;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;
use Berriart\Bundle\APMBundle\Client\ClientHandlerInterface;
use Berriart\Bundle\APMBundle\Model\Request as APMRequest;

/**
 * Tracks exceptions on APM
 */
class KernelListener
{
    const WATCH_NAME = 'berriart_apm.request';

    protected $kernel;
    protected $handler;
    protected $stopWatch;
    protected $rules;

    public function __construct(AppKernel $kernel, ClientHandlerInterface $handler, $rules)
    {
        $this->kernel = $kernel;
        $this->handler = $handler;
        $this->stopwatch = new Stopwatch();
        $this->rules = $rules;
    }

    /**
     * Handles the onKernelException event.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->rules['exceptions']) {
            $exception = $event->getException();

            if (!$exception instanceof HttpExceptionInterface) {
                $this->handler->trackException($exception);
            }
        }
    }

    /**
     * Handles the onKernelResponse event.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->rules['requests'] && $this->isTrackableRequest($event)) {
            $this->stopwatch->start(self::WATCH_NAME);
        }
    }

    /**
     * Handles the onKernelTerminate event.
     *
     * @param \Symfony\Component\HttpKernel\Event\PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        if ($this->rules['requests'] && $this->isTrackableRequest($event)) {
            $apmRequest = new APMRequest();

            $request = $event->getRequest();
            $response = $event->getResponse();

            $route = $request->get('_route') ?: 'unknown';
            $url = $request->getSchemeAndHttpHost().$event->getRequest()->getRequestUri();
            $startTime = $request->server->get('REQUEST_TIME');
            $httpResponseCode = $response->getStatusCode();
            $isSuccessful = $response->isSuccessful();
            $controllerName = $this->getControllerName($request);
            $duration = 0;
            $memoryUsage = 0;
            if ($this->stopwatch->isStarted(self::WATCH_NAME)) {
                $profile = $this->stopwatch->stop(self::WATCH_NAME);
                $duration = $profile->getDuration();
                $memoryUsage = $profile->getMemory();
            }

            $apmRequest->name = $route;
            $apmRequest->url = $url;
            $apmRequest->startTime = $startTime;
            $apmRequest->duration = $duration;
            $apmRequest->httpResponseCode = $httpResponseCode;
            $apmRequest->isSuccessful = $isSuccessful;
            $apmRequest->controller = $controllerName;
            $apmRequest->route = $route;
            $apmRequest->memory = $memoryUsage;
            $apmRequest->environment = $this->kernel->getEnvironment();

            $this->handler->trackRequest($apmRequest);
        }
    }

    protected function isTrackableRequest(KernelEvent $event)
    {
        $uri = $event->getRequest()->getRequestUri();
        if ($event->isMasterRequest() && !preg_match('#^/_profiler|^/_wdt#', $uri)) {
            return true;
        }

        return false;
    }

    protected function getControllerName(Request $request)
    {
        if (!$controller = $request->attributes->get('_controller')) {
            return false;
        }

        if (is_array($controller)) {
            return $controller;
        }

        if (is_object($controller)) {
            if (method_exists($controller, '__invoke')) {
                return $controller;
            }

            return false;
        }

        return $controller;
    }
}
