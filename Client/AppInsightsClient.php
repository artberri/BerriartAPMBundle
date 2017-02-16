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
    }

    public function getOriginalClient()
    {
        return $this->client;
    }

    public function hasToThrowExceptions()
    {
        return $this->throwExceptions;
    }

    public function trackException(\Exception $exception, $properties = array(), $measurements = array())
    {
        $properties = $this->addDefaultProperties($properties);
        $this->client->trackException($exception, $properties, $measurements);
        $this->client->flush();
    }

    public function trackRequest($name, $url, $startTime, $duration, $properties = array(), $measurements = array())
    {
        $customProperties = array();

        $propertyNames = array('httpResponseCode', 'isSuccessful');
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
        $this->client->flush();
    }

    public function trackMetric($name, $value, $properties = array())
    {
        $propertyNames = array('type', 'count', 'min', 'max', 'stdDev');
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
        $this->client->flush();
    }

    public function trackEvent($name, $properties = array(), $measurements = array())
    {
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackEvent($name, $properties, $measurements);
        $this->client->flush();
    }

    public function trackMessage($message, $properties = array())
    {
        $properties = $this->addDefaultProperties($properties);

        $this->client->trackEvent($message, $properties);
        $this->client->flush();
    }

    protected function splitArray($propertyNames, &$properties)
    {
        $customProperties = array();

        foreach ($propertyNames as $name) {
            $customProperties[$name] = null;
            if (isset($properties[$name])) {
                $customProperties[$name] = $properties[$name];
                unset($properties[$name]);
            }
        }

        return $customProperties;
    }
}
