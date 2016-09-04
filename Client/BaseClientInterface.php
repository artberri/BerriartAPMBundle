<?php

namespace Berriart\Bundle\APMBundle\Client;

use Berriart\Bundle\APMBundle\Model\Request;

interface BaseClientInterface
{
    /**
     * Tracks a run time exception
     *
     * @param  \RuntimeException $exception
     * @return self
     */
    public function trackException(\Exception $exception);

    /**
     * Tracks a run time exception
     *
     * @param  \Berriart\Bundle\APMBundle\Model\Request $request
     * @return self
     */
    public function trackRequest(Request $request);
}
