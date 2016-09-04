<?php

namespace Berriart\Bundle\APMBundle\Model;

use Berriart\Bundle\APMBundle\Exception\InvalidPropertyException;

abstract class AbstractModel
{
    public function __get($property)
    {
        $this->checkValidProperty($property);

        return $this->data[$property];
    }

    public function __set($property, $value)
    {
        $this->checkValidProperty($property);
        $this->data[$property] = $value;

        return $this;
    }

    protected function checkValidProperty($property)
    {
        if (!array_key_exists($property, $this->data)) {
            throw new InvalidPropertyException(
                'There is no property named '.$property.' in Berriart\Bundle\APMBundle\Model\Request.'
            );
        }
    }
}
