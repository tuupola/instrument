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
use Instrument\Transformer\InfluxDB as Transformer;

class InfluxDBTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldSetAndGetClient()
    {
        $client = \InfluxDB\Client::fromDSN("udp+influxdb://user:pass@127.0.0.1:8089/test");

        $adapter = new InfluxDB($client);
        $this->assertInstanceOf("InfluxDB\Database", $adapter->client());
    }

    public function testShouldSend()
    {
        $measurements = [];
        $measurements["users"] = new Count(["name" => "users", "value" => 10]);
        $measurements["roundtrip"] = new Timing(["name" => "roundtrip", "loadtime" => 1432]);
        $measurements["tickets"] = new Gauge(["name" => "tickets", "value" => 9923]);

        $client = \InfluxDB\Client::fromDSN("udp+influxdb://user:pass@127.0.0.1:8089/test");
        $adapter = new InfluxDB($client);

        $transformer = new Transformer;

        $adapter->send($transformer->transform($measurements));
        $sent = $adapter->measurements();
        $this->assertEquals("users", $sent["users"]->getMeasurement());
        $this->assertEquals("roundtrip", $sent["roundtrip"]->getMeasurement());
        $this->assertEquals("tickets", $sent["tickets"]->getMeasurement());
        $this->assertInstanceOf("InfluxDB\Point", $sent["users"]);
        $this->assertInstanceOf("InfluxDB\Point", $sent["roundtrip"]);
        $this->assertInstanceOf("InfluxDB\Point", $sent["tickets"]);
    }
}
