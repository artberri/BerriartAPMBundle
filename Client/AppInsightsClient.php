<?php

namespace Berriart\Bundle\APMBundle\Client;

use Berriart\Bundle\APMBundle\Model\Request;
use ApplicationInsights\Telemetry_Client as Client;

class AppInsightsClient implements ClientInterface
{
    protected $client;

    public function configure($config)
    {
        $this->client = new Client();
        $this->client->getContext()->setInstrumentationKey($config['api_key']);
    }

    public function getOriginalClient()
    {
        return $this->client;
    }

    public function trackException(\Exception $exception)
    {
        $this->client->trackException($exception);
        $this->client->flush();
    }

    public function trackRequest(Request $request)
    {
        $this->client->trackRequest(
            $request->name,
            $request->url,
            $request->startTime,
            $request->duration,
            $request->httpResponseCode,
            $request->isSuccessful,
            array(
                'Symfony Controller' => $request->controller,
                'Symfony Route' => $request->route,
                'Symfony Environment' => $request->environment,
            ),
            array(
                'Memory Usage' => $request->memory,

            )
        );
        $this->client->flush();
    }
}
