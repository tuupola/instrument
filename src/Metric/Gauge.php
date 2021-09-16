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
        $this->memory($memory);
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

        /* If increasing unset key init it as 0. */
        if (!isset($data[$key])) {
            $data[$key] = 0;
        }

        $data[$key] += $amount;
        $this->memory->write(json_encode($data));

        return $this;
    }

    public function decrease($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }
        $data = json_decode($this->memory->read(), true);

        /* If decreasing unset key init it as 0. */
        if (!isset($data[$key])) {
            $data[$key] = 0;
        }

        $data[$key] -= $amount;
        $this->memory->write(json_encode($data));

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
        return $this;
    }

    public function clear()
    {
        $this->delete();
        return $this;
    }

    public function destroy()
    {
        $this->memory->delete();
        return $this;
    }

    public function set($key, $value = null)
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

    public function get($key = "value")
    {
        $data = json_decode($this->memory->read(), true);
        if (isset($data[$key])) {
            return $data[$key];
        }
        return null;
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
    public function getValue($key = "value")
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->get($key);
    }

    public function fields()
    {
        $fields = json_decode($this->memory->read(), true);
        unset($fields["value"]);
        return $fields;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getFields()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->fields();
    }

    public function memory(SharedMemory $memory = null)
    {
        if (null === $memory) {
            return $this->memory;
        }
        $this->memory = $memory;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setMemory(SharedMemory $memory)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->memory = $memory;
        return $this;
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
        return "gauge";
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getType()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return "gauge";
    }
}
