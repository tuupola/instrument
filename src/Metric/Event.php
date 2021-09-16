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
        if (isset($this->value[$key])) {
            return $this->value[$key];
        }
        return null;
    }

    public function set($key, $value = null)
    {
        if (null === $value) {
            $value = $key;
            $key = "title";
        }
        $this->value[$key] = $value;
        return $this;
    }

    public function type()
    {
        return "event";
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getValue($key = "title")
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->get($key);
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setValue($key, $value = null)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->set($key, $value);
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getType()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return "event";
    }
}
