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
use PHPUnit\Framework\TestCase;

class MemoryTest extends TestCase
{

    public function testShouldSetAndGetClient()
    {
        $memory = new Memory("isalwaysright");
        $this->assertEquals("isalwaysright", $memory->client());
    }

    public function testShouldSend()
    {
        $measurements = [];
        $measurements["users"] = new Count(["name" => "users", "value" => 10]);
        $measurements["roundtrip"] = new Timing(["name" => "roundtrip", "loadtime" => 1432]);
        $measurements["tickets"] = new Gauge(["name" => "tickets", "value" => 9923]);

        $memory = new Memory();
        $memory->send($measurements);
        $sent = $memory->measurements();

        $this->assertEquals("users", $sent["users"]->name());
        $this->assertEquals("roundtrip", $sent["roundtrip"]->name());
        $this->assertEquals("tickets", $sent["tickets"]->name());
    }
}
