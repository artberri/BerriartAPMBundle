<?php

namespace Berriart\Bundle\APMBundle\Client;

abstract class AbstractClient implements ClientInterface
{
    protected function addDefaultProperties($properties)
    {
        $properties['Machine Hostname'] = $this->getHostName();

        return $properties;
    }

    protected function getHostName()
    {
        if (function_exists('gethostname')) {
            return gethostname();
        }

        if (function_exists('php_uname')) {
            return php_uname('n');
        }

        return 'unknown';
    }
}
