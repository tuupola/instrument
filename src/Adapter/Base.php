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
    use \Witchcraft\MagicMethods;

    protected $measurements = [];
    protected $client = null;

    public function __construct($client = null)
    {
        $this->setClient($client);
    }

    abstract public function send(array $points = []);

    public function setClient($client = null)
    {
        $this->client = $client;
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setMeasurements(array $measurements)
    {
        $this->measurements = $measurements;
        return $this;
    }

    public function getMeasurements()
    {
        return $this->measurements;
    }
}
