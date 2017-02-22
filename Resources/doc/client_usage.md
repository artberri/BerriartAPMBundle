# BerriartAPMBundle APM Service Wrapper Usage

In order to be service agnostic, all operations are handled by a client wrapper. This
client is exposed as a Symfony service to allow you track any event manually.

Note:

> Remember that exceptions and requests are tracked automatically by default and you
> don't need to do it manually.

## Accessing the service

The service is available in the container as the `berriart_apm`
service.

```php
$apmClient = $container->get('berriart_apm');
```

## Methods

Check the documentation in the [`Berriart\Bundle\APMBundle\Client/BaseClientInterface.php`](../../Client/BaseClientInterface.php)
interface to see the documentation about the available public methods:

- `$apmClient->trackException(\Exception $exception, $properties = [], $measurements = []);`
- `$apmClient->trackRequest($name, $url, $startTime, $duration, $properties = [], $measurements = []);`
- `$apmClient->trackEvent($name, $properties = [], $measurements = []);`
- `$apmClient->trackMetric($name, $value, $properties = []);`
- `$apmClient->trackMessage($message, $properties = []);`
- `$apmClient->trackDependency($name, $type = 0, $commandName = null, $startTime = null, $durationInMilliseconds = 0, $isSuccessful = true, $resultCode = null, $isAsync = null, $properties = []);`

## Other documentation

The following documents are available:

- [Installation](installation.md).
- [Command Line Tools](commands.md).
