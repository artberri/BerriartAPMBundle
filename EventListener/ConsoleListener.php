<?php

namespace Berriart\Bundle\APMBundle\EventListener;

use AppKernel;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Stopwatch\Stopwatch;
use Berriart\Bundle\APMBundle\Client\ClientHandlerInterface;

class ConsoleListener
{
    const WATCH_NAME = 'berriart_apm.command';

    protected $kernel;
    protected $handler;
    protected $stopWatch;
    protected $rules;

    public function __construct(AppKernel $kernel, ClientHandlerInterface $handler, $rules)
    {
        $this->kernel = $kernel;
        $this->handler = $handler;
        $this->stopwatch = new Stopwatch();
        $this->rules = $rules;
    }

    /**
     * @param ConsoleExceptionEvent $event
     */
    public function onConsoleException(ConsoleExceptionEvent $event)
    {
        if ($this->rules['exceptions']) {
            $exception = $event->getException();
            $this->handler->trackException($exception);
        }
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand()
    {
        if ($this->rules['commands']) {
            $this->stopwatch->start(self::WATCH_NAME);
        }
    }

    /**
     * @param ConsoleTerminateEvent $event
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $command = $event->getCommand();
        $input = $event->getInput();

        $properties = array(
            'Symfony Command Name' => $command->getName(),
            'Symfony Environment' => $this->kernel->getEnvironment(),
        );

        foreach ($input->getOptions() as $key => $value) {
            $key = 'Option: '.$key;
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $properties[$key.'['.$k.']'] = $v;
                }

                continue;
            }

            $properties[$key] = $value;
        }

        foreach ($input->getArguments() as $key => $value) {
            $key = 'Argument: '.$key;
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $properties[$key.'['.$k.']'] = $v;
                }

                continue;
            }

            $properties[$key] = $value;
        }

        $measurements = array();
        if ($this->stopwatch->isStarted(self::WATCH_NAME)) {
            $profile = $this->stopwatch->stop(self::WATCH_NAME);
            $measurements = array(
                'Memory Usage' => $profile->getMemory(),
                'Execution Duration' => $profile->getDuration(),
            );
        }

        $this->handler->trackEvent('Symfony Command Execution', $properties, $measurements);
    }
}
