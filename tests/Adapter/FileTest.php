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

use Instrument\Metric\Count;
use Instrument\Metric\Timing;
use Instrument\Metric\Gauge;

use Instrument\Transformer\InfluxDBLineProtocol as Transformer;

class FileTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldSetAndGetFile()
    {
        $file = new File();
        $file2 = new File("/tmp/isalwaysright.txt");
        $this->assertEquals("/tmp/instrument.txt", $file->file());
        $this->assertEquals("/tmp/isalwaysright.txt", $file2->file());
    }

    public function testShouldSend()
    {
        $measurements = [];
        $measurements["users"] = new Count(["name" => "users", "value" => 10]);
        $measurements[0] = new Timing(["name" => "roundtrip", "loadtime" => 1432]);
        $measurements[1] = new Timing(["name" => "roundtrip", "loadtime" => 1234]);
        $measurements["tickets"] = new Gauge(["name" => "tickets", "value" => 9923]);

        $adapter = new File();
        $transformer = new Transformer;

        $adapter->send($transformer->transform($measurements));
        $sent = $adapter->measurements();

        $this->assertEquals("users value=10i", $sent["users"]);
        $this->assertEquals("roundtrip loadtime=1432i", $sent["roundtrip-0"]);
        $this->assertEquals("roundtrip loadtime=1234i", $sent["roundtrip-1"]);
        $this->assertEquals("tickets value=9923i", $sent["tickets"]);
    }
}
