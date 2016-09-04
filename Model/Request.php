<?php

namespace Berriart\Bundle\APMBundle\Model;

class Request extends AbstractModel
{
    protected $data = array(
        'name' => null,
        'url' => null,
        'startTime' => null,
        'duration' => null,
        'httpResponseCode' => null,
        'isSuccessful' => null,
        'controller' => null,
        'route' => null,
        'memory' => null,
        'environment' => null,
    );
}
