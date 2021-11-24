<?php

namespace ShInUeXx\Generic;

use OutOfBoundsException;

use function sprintf;

trait GetterTrait
{
    public function __get(string $name)
    {
        if (isset($this->{$name}))
            return $this->{$name};
        else
            throw new OutOfBoundsException(sprintf('No property named: "%s"', $name));
    }
}
