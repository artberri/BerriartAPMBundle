<?php

namespace Berriart\Bundle\APMBundle\Client;

interface BaseClientInterface
{
    /**
     * Tracks a run time exception
     *
     * @param  \RuntimeException $exception
     * @param  array $properties An array of name to value pairs. Use the name as the index and any string as the value.
     * @param  array $measurements An array of name to double pairs. Use the name as the index and any double as the value.
     * @return self
     */
    public function trackException(\Exception $exception, $properties = array(), $measurements = array());

    /**
     * Tracks a request
     *
     * @param  string $name A friendly name of the request.
     * @param  string $url The url of the request.
     * @param  int $startTime The timestamp at which the request started.
     * @param  int $duration The duration, in milliseconds, of the request.
     * @param  array $properties An array of name to value pairs. Use the name as the index and any string as the value.
     * @param  array $measurements An array of name to double pairs. Use the name as the index and any double as the value.
     * @return self
     */
    public function trackRequest($name, $url, $startTime, $duration, $properties = array(), $measurements = array());

    /**
     * Tracks an event
     *
     * @param  string $name
     * @param  array $properties An array of name to value pairs. Use the name as the index and any string as the value.
     * @param  array $measurements An array of name to double pairs. Use the name as the index and any double as the value.
     * @return self
     */
    public function trackEvent($name, $properties = array(), $measurements = array());

    /**
     * Tracks a metric
     *
     * @param  string $name Name of the metric.
     * @param  double $value Value of the metric.
     * @param  array $properties An array of name to value pairs. Use the name as the index and any string as the value.
     * @return self
     */
    public function trackMetric($name, $value, $properties = array());

    /**
     * Tracks a message
     *
     * @param  string $message The trace message.
     * @param  array $properties An array of name to value pairs. Use the name as the index and any string as the value.
     * @return self
     */
    public function trackMessage($message, $properties = array());
}
