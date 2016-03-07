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

class Timing extends Base implements Metric
{
    use \Witchcraft\MagicProperties;

    private $stopwatch = null;
    private $memory = null;

    public function __construct($options = [])
    {
        $this->stopwatch = new Stopwatch;
        parent::__construct($options);
    }

    public function start($key = "value")
    {
        $this->stopwatch->start($key);
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

    public function setValue($key, $value = null)
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

    public function getMemory()
    {
        return $this->memory;
    }

    public function getType()
    {
        return "timing";
    }
}
