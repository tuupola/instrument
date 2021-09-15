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

namespace Instrument\Transformer;

use Instrument\Metric\Count;
use Instrument\Metric\Gauge;
use PHPUnit\Framework\TestCase;

class InfluxDBTest extends TestCase
{
    public function testShouldTransform()
    {
        $measurements = [];
        $count = new Count(["name" => "foo"]);
        $count->set(10);
        $count->set("users", 300);

        $gauge = new Gauge(["name" => "bar"]);
        $gauge->set("current", 400);

        $measurements[] = $count;
        $measurements[] = $gauge;

        $transformer = new InfluxDB;
        $result = $transformer->transform($measurements);
    }
}
