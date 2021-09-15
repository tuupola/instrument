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

namespace Instrument\Adapter;

use Instrument\Adapter;

abstract class Base implements Adapter
{
    //use \Witchcraft\MagicMethods;

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
