<?php

namespace ShInUeXx\Generic;

use function sprintf, spl_object_id;

trait StringableTrait
{
    public function __toString(): string
    {
        return sprintf('%s #%d', static::class, spl_object_id($this));
    }
}
