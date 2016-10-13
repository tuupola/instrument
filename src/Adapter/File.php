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

class File extends Base implements Adapter
{
    protected $file;

    public function __construct($file = "/tmp/instrument.txt")
    {
        $this->setFile($file);
    }

    public function send(array $points = [])
    {
        $this->setMeasurements($points);
        $string = implode("\n", $points) . "\n";
        file_put_contents($this->file, $string, FILE_APPEND | LOCK_EX);
    }

    public function setFile($file = null)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }
}
