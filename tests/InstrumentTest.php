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

namespace Instrument;

class InstrumentTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldCreateMetrics()
    {
        $instrument = new Instrument;
        $count = $instrument->count("users", 10);
        $this->assertEquals(10, $count->get());
        $this->assertEquals(10, $count->get("value"));
        $this->assertEquals(null, $count->get("nosuch"));

        $timing = $instrument->timing("roundtrip")->set("loadtime", 1432);
        $this->assertEquals(null, $timing->get());
        $this->assertEquals(null, $timing->get("value"));
        $this->assertEquals(null, $count->get("loadtime"));

        $gauge = $instrument->gauge("tickets", 9923);
        $this->assertEquals(9923, $gauge->get());
        $this->assertEquals(9923, $gauge->get("value"));
        $this->assertEquals(null, $gauge->get("nosuch"));
    }

    public function testShouldGetAndSetTransformer()
    {
        $instrument = new Instrument([
            "transformer" => new Transformer\InfluxDB
        ]);
        $this->assertInstanceOf("Instrument\Transformer\InfluxDB", $instrument->transformer());
    }

    public function testShouldGetAndSetAdapter()
    {
        $instrument = new Instrument([
            "adapter" => new Adapter\Memory
        ]);
        $this->assertInstanceOf("Instrument\Adapter\Memory", $instrument->adapter());
    }

    public function testShouldSend()
    {
        $instrument = new Instrument([
            "transformer" => new Transformer\InfluxDB,
            "adapter" => new Adapter\Memory
        ]);

        $count = $instrument->count("users", 10);
        $timing = $instrument->timing("roundtrip")->set("loadtime", 1432);
        $gauge = $instrument->gauge("tickets", 9923);

        $instrument->send();
        $adapter = $instrument->adapter();
        $sent = $adapter->measurements();

        $this->assertEquals("users", $sent["users"]->getMeasurement());
        $this->assertEquals("roundtrip", $sent["roundtrip"]->getMeasurement());
        $this->assertEquals("tickets", $sent["tickets"]->getMeasurement());
    }

    public function testShouldStartAndStopChainedTimer()
    {
        $instrument = new Instrument;

        $instrument->timing("test")->start();
        usleep(2500);
        $instrument->timing("test")->stop();

        $instrument->timing("test")->start("jump");
        usleep(3500);
        $instrument->timing("test")->stop("jump");

        $this->assertTrue($instrument->timing("test")->get() >= 2);
        $this->assertTrue($instrument->timing("test")->get("jump") >= 3);
    }

    public function testShouldMeasureChainedClosure()
    {
        $instrument = new Instrument;
        $instrument->timing("test")->closure(function () {
            usleep(2500);
        });
        $instrument->timing("test")->closure("dive", function () {
            usleep(3500);
        });

        $this->assertTrue($instrument->timing("test")->get() >= 2);
        $this->assertTrue($instrument->timing("test")->get("dive") >= 3);
    }

    public function testShouldReturnExistingMeasurement()
    {
        $instrument = new Instrument;
        $instrument->count("cars")->set("ford", 10);
        $count = $instrument->count("cars")->set("audi", 20);
        $this->assertEquals(10, $count->get("ford"));
        $this->assertEquals(20, $count->get("audi"));
        $this->assertEquals(null, $count->get("nosuch"));

        $instrument->timing("boot")->set("first", 1230);
        $timing = $instrument->timing("boot")->set("second", 20);
        $this->assertEquals(1230, $timing->get("first"));
        $this->assertEquals(20, $timing->get("second"));
        $this->assertEquals(null, $count->get("nosuch"));

        $instrument->gauge("users")->set("online", 992);
        $gauge = $instrument->gauge("users")->set("registered", 651237);
        $this->assertEquals(992, $gauge->get("online"));
        $this->assertEquals(651237, $gauge->get("registered"));
        $this->assertEquals(null, $gauge->get("nosuch"));
    }
}
