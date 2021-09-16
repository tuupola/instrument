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

namespace Instrument\Adapter;

use Instrument\Adapter;

abstract class Base implements Adapter
{
    protected $measurements = [];
    protected $client = null;

    public function __construct($client = null)
    {
        $this->client($client);
    }

    abstract public function send(array $points = []);

    public function client($client = null)
    {
        if (null === $client) {
            return $this->client;
        }
        $this->client = $client;
        return $this;
    }

    public function measurements(array $measurements = null)
    {
        if (null === $measurements) {
            return $this->measurements;
        }
        $this->measurements = $measurements;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setClient($client = null)
    {
        $this->client = $client;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getClient()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->client;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setMeasurements(array $measurements)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->measurements = $measurements;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getMeasurements()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->measurements;
    }


}
