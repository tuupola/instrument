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

class Memory extends Base implements Adapter
{
    use \Witchcraft\MagicMethods;

    public function send(array $measurements = [])
    {
        return $this->setMeasurements($measurements);
    }
}
