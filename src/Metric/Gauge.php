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
use Simple\SHM\Block as SharedMemory;

class Gauge extends Base implements Metric
{
    public $memory;

    public function __construct($options = [])
    {
        $id = ftok(__FILE__, "i");
        $memory = new SharedMemory($id);
        $this->setMemory($memory);
        if (!$this->memory->exists($id)) {
            $this->memory->write(json_encode([]));
        }

        parent::__construct($options);
    }

    public function increase($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }
        $data = json_decode($this->memory->read(), true);

        if (isset($data[$key])) {
            $data[$key] += $amount;
            $this->memory->write(json_encode($data));
        }
        return $this;
    }

    public function decrease($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }

        $data = json_decode($this->memory->read(), true);
        if (isset($data[$key])) {
            $data[$key] -= $amount;
            $this->memory->write(json_encode($data));
        }
        return $this;
    }

    public function delete($key = null)
    {
        if (null === $key) {
            $this->memory->write(json_encode([]));
        } else {
            $data = json_decode($this->memory->read(), true);
            if (isset($data[$key])) {
                unset($data[$key]);
                $this->memory->write(json_encode($data));
            }
        }
    }

    public function clear()
    {
        $this->delete();
    }

    public function destroy()
    {
        $this->memory->delete();
    }

    public function setValue($key, $value = null)
    {
        if (null === $value) {
            $value = $key;
            $key = "value";
        }

        $data = json_decode($this->memory->read(), true);
        $data[$key] = $value;
        $this->memory->write(json_encode($data));

        return $this;
    }

    public function getValue($key = "value")
    {
        $data = json_decode($this->memory->read(), true);
        if (isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    public function getFields()
    {
        $fields = json_decode($this->memory->read(), true);
        unset($fields["value"]);
        return $fields;
    }

    public function setMemory(SharedMemory $memory)
    {
        $this->memory = $memory;
    }

    public function getMemory()
    {
        return $this->memory;
    }

    public function getType()
    {
        return "gauge";
    }
}
