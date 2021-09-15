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
    public function setAdapter($adapter)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->adapter = $adapter;
        return $this;
    }

    /** @deprecated */
    public function getAdapter()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->adapter;
    }

    /** @deprecated */
    public function setTransformer($transformer)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->transformer = $transformer;
        return $this;
    }

    /** @deprecated */
    public function getTransformer()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->transformer;
    }

    /** @deprecated */
    public function getEvents()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->events;
    }

    /** @deprecated */
    public function getMeasurements()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->measurements;
    }
}
