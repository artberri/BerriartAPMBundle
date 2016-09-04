<?php

namespace Berriart\Bundle\APMBundle\Client;

use Berriart\Bundle\APMBundle\Model\Request;
use Berriart\Bundle\APMBundle\Client\ClientInterface;

class ClientHandler implements ClientHandlerInterface, BaseClientInterface
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

    public function trackException(\Exception $exception)
    {
        return $this->batch('trackException', $exception);
    }

    public function trackRequest(Request $request)
    {
        return $this->batch('trackRequest', $request);
    }

    protected function batch($method, $argument)
    {
        foreach ($this->clients as $client) {
            call_user_func(array($client, $method), $argument);
        }

        return $this;
    }
}
