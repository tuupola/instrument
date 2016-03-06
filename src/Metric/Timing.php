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
use Symfony\Component\Stopwatch\Stopwatch;

class Timing extends Base implements Metric
{
    private $stopwatch = null;

    public function __construct($options = [])
    {
        $options["stopwatch"] = new Stopwatch;
        $this->hydrate($options);
    }

    public function start($key = "value")
    {
        $this->stopwatch->start($key);
        return $this;
    }

    public function stop($key = "value")
    {
        $event = $this->stopwatch->stop($key);
        $duration = $event->getDuration();
        $this->set($key, $duration);
        return $this;
    }

    public function closure($key = "value", \Closure $function = null)
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

    public function setStopwatch(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
        return $this;
    }

    public function getStopWatch()
    {
        return $this->stopwatch;
    }

    public function getType()
    {
        return "timing";
    }
}
