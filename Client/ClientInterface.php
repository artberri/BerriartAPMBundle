<?php

namespace Berriart\Bundle\APMBundle\Client;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

interface ClientInterface extends BaseClientInterface
{
    /**
     * Sets client configuration
     *
     * @param  array $config
     * @return self
     */
    public function configure($config);

    /**
     * Gets the APM original client
     *
     * @return Original APM Client
     */
    public function getOriginalClient();
}
