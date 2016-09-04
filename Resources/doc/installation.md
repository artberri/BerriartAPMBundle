# Installation

Installation guide for BerriartAPMBundle.

## Prerequisites

This version of the bundle requires Symfony >=2.6 and PHP >=5.4.2.

This bundle is intended to work with third party APM (Application Performance Monitoring) services.
You need to have an account in at least one of the supported services to continue.

**Supported APM services on this version**

- [Visual Studio Application Insights](https://azure.microsoft.com/en-us/services/application-insights/).
Please see the [Getting an Application Insights Instrumentation Key](https://github.com/Microsoft/AppInsights-Home/wiki#getting-an-application-insights-instrumentation-key)
section for more information.

## Installation

Installation is a quick 3 step process:

1. Download BerriartAPMBundle using composer
2. Enable the Bundle
3. Configure the BerriartAPMBundle

### Step 1: Download FOSUserBundle using composer

Require the bundle with composer:

```bash
$ composer require berriart/apm-bundle
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

Add the following configuration to your ``config.yml`` file according to which type
of datastore you are using.

TODO

Next Steps
~~~~~~~~~~

Now that you have completed the basic installation and configuration of the
BerriartAPMBundle, you are ready to learn about more advanced features and usages
of the bundle.

The following documents are available:

TODO
