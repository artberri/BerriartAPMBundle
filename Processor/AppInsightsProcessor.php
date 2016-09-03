<?php

namespace Berriart\Bundle\APMBundle\Processor;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;
use ApplicationInsights\Telemetry_Client as Client;

class AppInsightsProcessor implements ProcessorInterface
{
    const WATCH_NAME = 'berriart_apm.app_insights.request';

    protected $client;
    protected $stopWatch;

    public function configure($config)
    {
        $this->client = new Client();
        $this->client->getContext()->setInstrumentationKey($config['api_key']);
        $this->stopwatch = new Stopwatch();        
    }

    public function trackException(\Exception $exception)
    {
        $this->client->trackException($exception);
        $this->client->flush();
    }

    public function trackRequest(GetResponseEvent $event)
    {
        $this->stopwatch->start(self::WATCH_NAME);
    }

    public function trackResponse(PostResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $durationInMilliseconds = 0;
        $memoryUsage = 0;
        if ($this->stopwatch->isStarted(self::WATCH_NAME)) {
            $profile = $this->stopwatch->stop(self::WATCH_NAME);
            $durationInMilliseconds = $profile->getDuration();
            $memoryUsage = $profile->getMemory();
        }

        $name = $request->get('_route') ?: 'unknown';
        $url = $request->getSchemeAndHttpHost() . $event->getRequest()->getRequestUri();
        $startTime = $request->server->get('REQUEST_TIME');
        $httpResponseCode = $response->getStatusCode();
        $isSuccessful = $response->isSuccessful();
        $controllerName = $this->getControllerName($request);

        $this->client->trackRequest(
            $name, 
            $url, 
            $startTime, 
            $durationInMilliseconds, 
            $httpResponseCode, 
            $isSuccessful, 
            $properties = array(
                'Symfony Controller' => $controllerName,
                'Symfony Route' => $name,
            ), 
            $measurements = array(
                'Memory Usage' => $memoryUsage,
                
            )
        );
        $this->client->flush();
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

            throw new \InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', get_class($controller), $request->getPathInfo()));
        }

        return $controller;
    }
}
