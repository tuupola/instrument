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

use Closure;
use Instrument\Metric;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

class Timing extends Base implements Metric
{
    private $stopwatch = null;
    private $memory = null;
    private $keys = [];

    public function __construct($options = [])
    {
        $this->stopwatch = new Stopwatch;
        parent::__construct($options);
    }

    public function start($key = "value")
    {
        $this->stopwatch->start($key);
        array_push($this->keys, $key);
        return $this;
    }

    public function stop($key = "value")
    {
        if ($this->stopwatch->isStarted($key)) {
            $event = $this->stopwatch->stop($key);
            $duration = $event->getDuration();
            $this->memory = $event->getMemory();
            $this->set($key, $duration);
        }
        return $this;
    }

    public function stopAll()
    {
        foreach ($this->keys as $key) {
            $this->stop($key);
        }
        return $this;
    }

    public function closure($key = "value", Closure $function = null)
    {
        if (is_callable($key)) {
            $function = $key;
            $key = "value";
        }
        $this->start($key);
        $return = $function();
        $this->stop($key);
        return $return;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setValue($key, $value = null)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        if (null === $value) {
            $value = $key;
            $key = "value";
        }

        /* Allow calling $timing->set("fly", function () {...}) */
        if ($value instanceof Closure) {
            $this->closure($key, $value);
        } else {
            $this->value[$key] = $value;
        }
        return $this;
    }

    public function set($key, $value = null)
    {
        if (null === $value) {
            $value = $key;
            $key = "value";
        }

        /* Allow calling $timing->set("fly", function () {...}) */
        if ($value instanceof Closure) {
            $this->closure($key, $value);
        } else {
            $this->value[$key] = $value;
        }
        return $this;
    }

    public function getStopWatch()
    {
        return $this->stopwatch;
    }

    public function memory()
    {
        return $this->memory;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getMemory()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->memory;
    }

    public function type()
    {
        return "timing";
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getType()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return "timing";
    }
}
