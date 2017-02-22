<?php

namespace Berriart\Bundle\APMBundle\Client;

use ApplicationInsights\Telemetry_Client as Client;

class AppInsightsClient extends AbstractClient implements ClientInterface
{
    protected $client;
    protected $throwExceptions;

    public function configure($config)
    {
        $this->client = new Client();
        $this->client->getContext()->setInstrumentationKey($config['api_key']);
        $this->throwExceptions = $config['throw_exceptions'];
        $this->sendOnTerminate = $config['send_onterminate'];
    }

    public function getOriginalClient()
    {
        return $this->client;
    }

    public function hasToThrowExceptions()
    {
        return $this->throwExceptions;
    }

    public function trackException(\Exception $exception, $properties = [], $measurements = [])
    {
        $properties = $this->addDefaultProperties($properties);
        $this->client->trackException($exception, $properties, $measurements);
        $this->queue();
    }

    public function trackRequest($name, $url, $startTime, $duration, $properties = [], $measurements = [])
    {
        $customProperties = [];

        $propertyNames = ['httpResponseCode', 'isSuccessful'];
        $customProperties = $this->splitArray($propertyNames, $properties);
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackRequest(
            $name,
            $url,
            $startTime,
            $duration,
            $customProperties['httpResponseCode'],
            $customProperties['isSuccessful'],
            $properties,
            $measurements
        );
        $this->queue();
    }

    public function trackMetric($name, $value, $properties = [])
    {
        $propertyNames = ['type', 'count', 'min', 'max', 'stdDev'];
        $customProperties = $this->splitArray($propertyNames, $properties);
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackMetric(
            $name,
            $value,
            $customProperties['type'],
            $customProperties['count'],
            $customProperties['min'],
            $customProperties['max'],
            $customProperties['stdDev'],
            $properties
        );
        $this->queue();
    }

    public function trackEvent($name, $properties = [], $measurements = [])
    {
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackEvent($name, $properties, $measurements);
        $this->queue();
    }

    public function trackMessage($message, $properties = [])
    {
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackEvent($message, $properties);
        $this->queue();
    }

    public function trackDependency(
        $name,
        $type = \ApplicationInsights\Channel\Contracts\Dependency_Type::OTHER,
        $commandName = null,
        $startTime = null,
        $durationInMs = 0,
        $isSuccessful = true,
        $resultCode = null,
        $isAsync = null,
        $properties = []
    ) {
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackDependency(
            $name,
            $type,
            $commandName,
            $startTime,
            $durationInMs,
            $isSuccessful,
            $resultCode,
            $isAsync,
            $properties
        );
        $this->queue();
    }

    public function flush()
    {
        $this->client->flush();
    }

    protected function splitArray($propertyNames, &$properties)
    {
        $customProperties = [];

        foreach ($propertyNames as $name) {
            $customProperties[$name] = null;
            if (isset($properties[$name])) {
                $customProperties[$name] = $properties[$name];
                unset($properties[$name]);
            }
        }

        return $customProperties;
    }

    protected function queue()
    {
        // Is only sended inmediately when `send_onterminate` is not active
        if (!$this->sendOnTerminate) {
            $this->client->flush();
        }
    }
}
