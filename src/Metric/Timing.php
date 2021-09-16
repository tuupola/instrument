<?php

/*

Copyright (c) 2016-2021 Mika Tuupola

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

/**
 * @see       https://github.com/tuupola/instrument
 * @license   https://www.opensource.org/licenses/mit-license.php
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
        return $this->set($key, value);
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
