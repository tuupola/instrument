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
use InfluxDB\Database;

class InfluxDB extends Base implements Adapter
{
    public function __construct(Database $client)
    {
        $this->client($client);
    }

    public function send(array $points = [])
    {
        $this->measurements($points);
        return $this->client->writePoints($points);
    }
}
