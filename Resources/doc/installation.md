# BerriartAPMBundle Installation

Installation guide for BerriartAPMBundle.

## Prerequisites

This version of the bundle requires Symfony >=2.6 and PHP >=5.4.2.

This bundle is intended to work with third party APM (Application Performance Monitoring) services.
You need to have an account in at least one of the supported services to continue.

### Supported APM services on this version

- [Visual Studio Application Insights](https://azure.microsoft.com/en-us/services/application-insights/): You need to get an Instrumentation
Key to add it to the configuration, please see the [Getting an Application Insights Instrumentation Key](https://github.com/Microsoft/AppInsights-Home/wiki#getting-an-application-insights-instrumentation-key)
section for more information. Don't forget [adding the client side SDK](https://azure.microsoft.com/en-gb/documentation/articles/app-insights-javascript/)
in your `base.html.twig` file for full monitoring (sorry, we can't do this automatically).

## Installation

Installation is a quick 3 step process:

1. Download BerriartAPMBundle using composer
1. Enable the Bundle
1. Configure the BerriartAPMBundle

### Step 1: Download FOSUserBundle using composer

Require the bundle with composer:

```bash
composer require berriart/apm-bundle
```

Composer will install the bundle to your project's `vendor/berriart/apm-bundle` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Berriart\Bundle\APMBundle\BerriartAPMBundle(),
        // ...
    );
}
```

### Step 3: Configure the BerriartAPMBundle

Add the following configuration to your ``config.yml`` file according to which APM services
you are using.

Bellow all configuration values are listed with their respective default values, but only the
service configuration values are required to use the bundle (for example, if you are using
Application Insights, the only required value is the `api_key`):

```yaml
berriart_apm:
    alias: berriart_apm # Alias for the APM service wrapper
    listeners:
        exceptions: true # Track all exceptions automatically
        requests: true # Track all requests automatically
        commands: true # Track all executed console commands automatically
    services:
        app_insights:
            api_key: ca8f0d5f-cce8-438c-aad7-71112d9a4379 # The integration key for VS Application Insights
            priority: 0 # Sets the priority of the service (it will only affect the execution order)
            throw_exceptions: true # Variable to set if you want to stop propagation of the exception or not
            send_onterminate: false # All requests will be made on kernel.terminate (only PHP-FPM, http://symfony.com/doc/current/components/http_kernel.html#component-http-kernel-kernel-terminate)
```

### Next Steps

Now that you have completed the basic installation and configuration of the
BerriartAPMBundle, you are ready to learn about more advanced features and usages
of the bundle.

The following documents are available:

- [APM Service Wrapper Usage](client_usage.md).
- [Command Usage](commands.md).
