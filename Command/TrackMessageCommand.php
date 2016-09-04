<?php

namespace Berriart\Bundle\APMBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TrackMessageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('apm:track:message')
            ->setDescription('Sends a message to APM services')
            ->setHelp('This command allows you to send a message to be tracked on the APM services configured on the BerriartAPMBundle')
            ->addArgument('message', InputArgument::REQUIRED, 'The message to be sended.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $apmClient = $this->getContainer()->get('berriart_apm');
        $message = $input->getArgument('message');

        $apmClient->trackMessage($message);

        $output->writeln('APM Message tracked: '.$message);
    }
}
