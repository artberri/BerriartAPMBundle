<?php

namespace Berriart\Bundle\APMBundle\Client;

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

    /**
     * Gets if the client must throw the exceptions
     *
     * @return boolean
     */
    public function hasToThrowExceptions();
}
