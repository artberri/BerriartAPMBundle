<?php

namespace Berriart\Bundle\APMBundle\Client;

use Berriart\Bundle\APMBundle\Client\ClientInterface;

class ClientHandler implements ClientHandlerInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = [];
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

    public function trackException(\Exception $exception, $properties = [], $measurements = [])
    {
        return $this->batch('trackException', [$exception, $properties, $measurements]);
    }

    public function trackRequest($name, $url, $startTime, $duration, $properties = [], $measurements = [])
    {
        return $this->batch('trackRequest', [$name, $url, $startTime, $duration, $properties, $measurements]);
    }

    public function trackEvent($name, $properties = [], $measurements = [])
    {
        return $this->batch('trackEvent', [$name, $properties, $measurements]);
    }

    public function trackMetric($name, $value, $properties = [])
    {
        return $this->batch('trackMetric', [$name, $value, $properties]);
    }

    public function trackMessage($message, $properties = [])
    {
        return $this->batch('trackMessage', [$message, $properties]);
    }

    public function trackDependency(
        $name,
        $type = 0,
        $commandName = null,
        $startTime = null,
        $durationInMs = 0,
        $isSuccessful = true,
        $resultCode = null,
        $isAsync = null,
        $properties = []
    ) {
        return $this->batch('trackDependency', [
            $name,
            $type,
            $commandName,
            $startTime,
            $durationInMs,
            $isSuccessful,
            $resultCode,
            $isAsync,
            $properties,
        ]);
    }

    public function flush()
    {
        return $this->batch('flush', []);
    }

    protected function batch($method, $arguments)
    {
        foreach ($this->clients as $client) {
            try {
                call_user_func_array([$client, $method], $arguments);
            } catch (\Exception $e) {
                $throwExceptions = call_user_func_array([$client, 'hasToThrowExceptions'], []);
                if ($throwExceptions) {
                    throw $e;
                }
            }
        }

        return $this;
    }
}
