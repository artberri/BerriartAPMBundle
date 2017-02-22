# BerriartAPMBundle Command Line Tools

The BerriartAPMBundle provides a command line utility for sending messages to your
APM service:

Note:

> You must have correctly installed and configured the FOSUserBundle before
> using these commands.

Note:

> This documentation references the console as `bin/console`, which is
> the Symfony 3 location. If you use Symfony 2.x, use `app/console` instead.

## Usage

You can use the `apm:track:message` command to send a message to your APM
service.

The command takes only one argument, the message to be sent.

For example if you wanted to send a deployment message, you would run the command as follows.

```bash
php bin/console apm:track:message "Release v1.4.2"
```

## Other documentation

The following documents are available:

- [Installation](installation.md).
- [APM Service Wrapper Usage](client_usage.md).
