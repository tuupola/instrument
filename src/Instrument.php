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
    use \Witchcraft\Hydrate;
    use \Witchcraft\MagicMethods;

    private $adapter = null;
    private $transformer = null;
    private $measurements = [];
    private $events = [];
    private $start = null;
    private $end = null;

    public function __construct($options = [])
    {
        $this->hydrate($options);
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

    public function send($clear = true)
    {
        $measurements = $this->transformer->transform($this->measurements);
        $events = $this->transformer->transform($this->events);
        $this->adapter->send($measurements + $events);

        if (true === $clear) {
            $this->measurements = [];
            $this->events = [];
        }
        return $this;
    }

    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function getTransformer()
    {
        return $this->transformer;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getMeasurements()
    {
        return $this->measurements;
    }
}
