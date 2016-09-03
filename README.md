# BerriartAPMBundle

[![Build Status](https://travis-ci.org/artberri/BerriartAPMBundle.svg?branch=master)](https://travis-ci.org/artberri/BerriartAPMBundle)

Seamless integration between APM (Application Performance Monitoring) services and Symfony projects.

## Summary

The BerriartAPMBundle integrates Symfony with APM services.
It provides a flexible framework for user management that aims to handle
common tasks such as user registration and password retrieval.

APM services included:

- [Visual Studio Application Insights](https://azure.microsoft.com/en-us/services/application-insights/)
- [New Relic](https://newrelic.com/) (coming soon)

Features include:

- **Multi APM support**: you can use as many APM providers as you want. Usually only one is used but is usefull
for migrations. If you APM provider is not supported by this bundle you can create your own and contribute to the project
or make us a suggestion.
- **Request monitoring**: Every request is tracked including: status code, url, duration, memory usage,
constroller and symfony route name.
- **Exception tracking**: Every Symfony exception is tracked.

## Documentation

The source of the documentation is stored in the `Resources/doc/` folder in this bundle:

- [Installation](Resources/doc/installation.md).

## Installation

All the installation instructions are located in the [documentation folder](Resources/doc/installation.md).

## License

This bundle is available under the [MIT License](LICENSE).

# About

BerriartAPMBundle is a [Berriart](http://www.berriart.com) initiative.

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/artberri/BerriartAPMBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
