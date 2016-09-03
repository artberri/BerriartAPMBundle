<?php

namespace Berriart\Bundle\APMBundle\Processor;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

interface ProcessorInterface
{
    /**
     * Sets processor configuration
     *
     * @param  array $config
     * @return self
     */
    public function configure($config);

    /**
     * Gets the APM client
     *
     * @return APM Client
     */
    public function getClient();

    /**
     * Tracks a run time exception
     *
     * @param  \RuntimeException $exception
     * @return self
     */
    public function trackException(\Exception $exception);

    /**
     * Tracks a run time exception
     *
     * @param  \Symfony\Component\HttpKernel\Event\GetResponseEvent $request
     * @return self
     */
    public function trackRequest(GetResponseEvent $event);

    /**
     * Tracks a run time exception
     *
     * @param  \Symfony\Component\HttpKernel\Event\PostResponseEvent $response
     * @return self
     */
    public function trackResponse(PostResponseEvent $event);
}
