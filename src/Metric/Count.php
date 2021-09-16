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

class Count extends Base implements Metric
{
    public function increase($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }

        /* If increasing unset key init it as 0. */
        if (!isset($this->value[$key])) {
            $this->value[$key] = 0;
        }
        $this->value[$key] += $amount;
        return $this;
    }

    public function decrease($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }

        /* If decresing unset key init it as 0. */
        if (!isset($this->value[$key])) {
            $this->value[$key] = 0;
        }
        $this->value[$key] -= $amount;
        return $this;
    }

    public function type()
    {
        return "count";
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getType()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return "count";
    }
}
