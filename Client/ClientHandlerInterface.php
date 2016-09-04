<?php

namespace Berriart\Bundle\APMBundle\Client;

use Berriart\Bundle\APMBundle\Client\ClientInterface;

/**
 * Interface that all Client Handlers must implement
 */
interface ClientHandlerInterface
{
    /**
     * Adds a client in the stack.
     *
     * @param  \Berriart\Bundle\APMBundle\Client\ClientInterface $client
     * @param  string $clientName Name of the client
     * @return self
     */
    public function addClient(ClientInterface $client, $clientName);

    /**
     * Gets a client from the stack.
     *
     * @param  string $clientName Name of the client
     * @return \Berriart\Bundle\APMBundle\Client\ClientInterface
     */
    public function getClient($clientName);

    /**
     * Gets a client from the stack.
     *
     * @param  string $clientName Name of the client
     * @return self
     */
    public function removeClient($clientName);
}
