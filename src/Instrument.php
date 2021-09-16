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

namespace Instrument;

class Instrument
{
    private $adapter;
    private $transformer;
    private $measurements;
    private $events;
    private $start;
    private $end;

    public function __construct($options = [])
    {
        $this->adapter = $options["adapter"] ?? null;
        $this->transformer = $options["transformer"] ?? null;
        $this->measurements = $options["measurements"] ?? [];
        $this->events = $options["events"] ?? [];
        $this->start = $options["adapter"] ?? null;
        $this->end = $options["adapter"] ?? null;
    }

    public function timing($name, $value = null)
    {
        if (!isset($this->measurements[$name])) {
            $this->measurements[$name] = new Metric\Timing([
                "name" => $name,
                "value" => $value
            ]);
        }
        return $this->measurements[$name];
    }

    public function count($name, $value = null)
    {
        if (!isset($this->measurements[$name])) {
            $this->measurements[$name] = new Metric\Count([
                "name" => $name,
                "value" => $value
            ]);
        }
        return $this->measurements[$name];
    }

    public function gauge($name, $value = null)
    {
        if (!isset($this->measurements[$name])) {
            $this->measurements[$name] = new Metric\Gauge([
                "name" => $name,
                "value" => $value
            ]);
        }
        return $this->measurements[$name];
    }

    public function event($title, $description = null)
    {
        $event = new Metric\Event([
            "name" => "events",
            "title" => $title,
            "description" => $description
        ]);
        $this->events[] = $event;
        return $event;
    }

    public function send($object = null)
    {

        if (null === $object) {
            /* Send all measurements and events. */
            $this->stopTimers();
            $measurements = $this->transformer->transform($this->measurements);
            $events = $this->transformer->transform($this->events);
            $this->adapter->send($measurements + $events);
            $this->clear();
        } else {
            /* Send given measurement or event. */
            $single = $this->transformer->transform([$object->name() => $object]);
            $this->adapter->send($single);
            $this->delete($object);
        }

        return $this;
    }

    public function clear()
    {
        $this->measurements = [];
        $this->events = [];
        return $this;
    }

    public function stopTimers()
    {
        foreach ($this->measurements as $measurement) {
            if ($measurement instanceof \Instrument\Metric\Timing) {
                $measurement->stopAll();
            }
        }
        return $this;
    }

    public function delete(Metric $object)
    {
        if ($object instanceof \Instrument\Metric\Event) {
            /* All events have the same name, compare objects instead. */
            $this->events = array_filter($this->events, function ($event) use ($object) {
                return $event !== $object;
            });
        } else {
            /* This is probably faster anyway. */
            unset($this->measurements[$object->name()]);
        }

        return $this;
    }

    public function adapter()
    {
        return $this->adapter;
    }

    public function transformer($transformer = null) {
        if (null === $transformer) {
            return $this->transformer;
        }
        $this->transformer = $transformer;
        return $this;
    }

    public function events()
    {
        return $this->events;
    }

    public function measurements()
    {
        return $this->measurements;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setAdapter($adapter)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->adapter = $adapter;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getAdapter()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->adapter;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setTransformer($transformer)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->transformer = $transformer;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getTransformer()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->transformer;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getEvents()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->events;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getMeasurements()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->measurements;
    }
}
