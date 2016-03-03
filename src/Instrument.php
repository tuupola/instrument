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
    private $start = null;
    private $end = null;

    public function __construct($options = [])
    {
        $this->hydrate($options);
    }

    public function timing($name, $value = null)
    {
        $data = $this->createData($name, $value);

        if (!isset($this->measurements[$data["name"]])) {
            $this->measurements[$data["name"]] = new Metric\Timing($data);
        }
        return $this->measurements[$data["name"]];
    }

    public function count($name, $value)
    {
        $data = $this->createData($name, $value);

        if (!isset($this->measurements[$data["name"]])) {
            $this->measurements[$data["name"]] = new Metric\Count($data);
        }
        return $this->measurements[$data["name"]];
    }

    public function gauge($name, $value)
    {
        $data = $this->createData($name, $value);

        if (!isset($this->measurements[$data["name"]])) {
            $this->measurements[$data["name"]] = new Metric\Gauge($data);
        }
        return $this->measurements[$data["name"]];
    }

    public function send()
    {
        $measurements = $this->transformer->transform($this->measurements);
        $this->adapter->send($this->measurements);
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

    private function createData($name, $value)
    {
        if (is_array($name)) {
            $data["name"] = $name[0];
            $data[$name[1]] = $value;
        } else {
            $data["name"] = $name;
            $data["value"] = $value;
        }
        return $data;
    }
}
