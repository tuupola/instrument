<?php

/*
 * This file is part of Instrument package
 *
 * Copyright (c) 2016 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/instrument
 *
 */

namespace Instrument\Metric;

use Instrument\Metric;

class Event extends Base implements Metric
{
    public function get($key = "title")
    {
        return $this->getValue($key);
    }

    public function getValue($key = "title")
    {
        if (isset($this->value[$key])) {
            return $this->value[$key];
        }
        return null;
    }

    public function setValue($key, $value = null)
    {
        if (null === $value) {
            $value = $key;
            $key = "title";
        }
        $this->value[$key] = $value;
        return $this;
    }

    public function getType()
    {
        return "event";
    }
}
