<?php

namespace Berriart\Bundle\APMBundle\Client;

use Berriart\Bundle\APMBundle\Client\ClientInterface;

class ClientHandler implements ClientHandlerInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = array();
    }

    public function addClient(ClientInterface $client, $clientName)
    {
        $this->clients[$clientName] = $client;

        return $this;
    }

    public function getClient($clientName)
    {
        return $this->clients[$clientName];
    }

    public function removeClient($clientName)
    {
        unset($this->clients[$clientName]);

        return $this;
    }

    public function trackException(\Exception $exception, $properties = array(), $measurements = array())
    {
        return $this->batch('trackException', array($exception, $properties, $measurements));
    }

    public function trackRequest($name, $url, $startTime, $duration, $properties = array(), $measurements = array())
    {
        return $this->batch('trackRequest', array($name, $url, $startTime, $duration, $properties, $measurements));
    }

    public function trackEvent($name, $properties = array(), $measurements = array())
    {
        return $this->batch('trackEvent', array($name, $properties, $measurements));
    }

    public function trackMetric($name, $value, $properties = array())
    {
        return $this->batch('trackMetric', array($name, $value, $properties));
    }

    public function trackMessage($message, $properties = array())
    {
        return $this->batch('trackMessage', array($message, $properties));
    }

    protected function batch($method, $arguments)
    {
        foreach ($this->clients as $client) {
            try {
                call_user_func_array(array($client, $method), $arguments);
            } catch (\Exception $e) {
                $throwExceptions = call_user_func_array(array($client, 'getThrowExceptions'), []);
                if ($throwExceptions) {
                    throw $e;
                }
            }
        }

        return $this;
    }
}
