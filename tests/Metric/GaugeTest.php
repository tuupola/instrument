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

namespace Instrument\Metric;

class GaugeTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldSetAndGetName()
    {
        $gauge = new Gauge(["name" => "butterflies"]);
        $this->assertEquals("butterflies", $gauge->name());
        $gauge->destroy();
    }

    public function testShouldSetAndGetValue()
    {
        $gauge = new Gauge();
        $gauge->set(10);
        $gauge->set("users", 300);
        $this->assertEquals(10, $gauge->get());
        $this->assertEquals(10, $gauge->get("value"));
        $this->assertEquals(300, $gauge->get("users"));
        $this->assertEquals(null, $gauge->get("nosuch"));
        $gauge->destroy();
    }

    public function testShouldGetFields()
    {
        $gauge = new Gauge();
        $gauge->set(10);
        $gauge->set("users", 300);
        $gauge->set("logins", 500);
        $this->assertEquals(["users" => 300, "logins" => 500], $gauge->fields());
        $gauge->destroy();
    }

    public function testShouldIncreaseValue()
    {
        $gauge = new Gauge();
        $gauge->set(10);
        $gauge->set("users", 300);

        $gauge->increase();
        $gauge->increase("users");
        $this->assertEquals(11, $gauge->get());
        $this->assertEquals(301, $gauge->get("users"));

        $gauge->increase(10);
        $gauge->increase("users", 10);
        $this->assertEquals(21, $gauge->get());
        $this->assertEquals(311, $gauge->get("users"));
        $gauge->destroy();
    }

    public function testShouldDecreaseValue()
    {
        $gauge = new Gauge();
        $gauge->set(10);
        $gauge->set("users", 300);

        $gauge->decrease();
        $gauge->decrease("users");
        $this->assertEquals(9, $gauge->get());
        $this->assertEquals(299, $gauge->get("users"));

        $gauge->decrease(4);
        $gauge->decrease("users", 10);
        $this->assertEquals(5, $gauge->get());
        $this->assertEquals(289, $gauge->get("users"));
        $gauge->destroy();
    }

    public function testShouldDelete()
    {
        $gauge = new Gauge();
        $gauge->set(10);
        $gauge->set("users", 300);
        $gauge->set("logins", 500);
        $this->assertEquals(10, $gauge->get());
        $this->assertEquals(300, $gauge->get("users"));
        $this->assertEquals(500, $gauge->get("logins"));
        $gauge->delete("users");
        $this->assertEquals(10, $gauge->get());
        $this->assertEquals(null, $gauge->get("users"));
        $this->assertEquals(500, $gauge->get("logins"));
        $gauge->clear();
        $this->assertEquals(null, $gauge->get());
        $this->assertEquals(null, $gauge->get("users"));
        $this->assertEquals(null, $gauge->get("logins"));
        //$gauge->destroy();
    }

    public function testShouldRememberValues()
    {
        $gauge = new Gauge();

        $gauge->set(66);
        $gauge->set("users", 666);
        $this->assertEquals(66, $gauge->get());
        $this->assertEquals(66, $gauge->get("value"));
        $this->assertEquals(666, $gauge->get("users"));
        $this->assertEquals(null, $gauge->get("nosuch"));
        unset($gauge);

        $gauge2 = new Gauge();
        $this->assertEquals(66, $gauge2->get());
        $this->assertEquals(66, $gauge2->get("value"));
        $this->assertEquals(666, $gauge2->get("users"));
        $this->assertEquals(null, $gauge2->get("nosuch"));
        $gauge2->increase("users");
        unset($gauge2);

        $gauge3 = new Gauge();
        $this->assertEquals(667, $gauge3->get("users"));

        $gauge3->destroy();
    }

    public function testShouldBeChainable()
    {
        $gauge = new Gauge();
        $gauge->set(10)->increase(5)->decrease(2)->name("butterflies");
        $gauge->set("users", 300)->increase("users", 5)->decrease("users", 100);

        $this->assertEquals("butterflies", $gauge->name());
        $this->assertEquals(13, $gauge->get());
        $this->assertEquals(205, $gauge->get("users"));
        $gauge->destroy();
    }

    public function testShouldReturnType()
    {
        $gauge = new Gauge();
        $this->assertEquals("gauge", $gauge->type());
        $gauge->destroy();
    }

    public function testShouldSetAndGetMemory()
    {
        $gauge = new Gauge();
        $this->assertInstanceOf("Simple\SHM\Block", $gauge->memory());
        $gauge->destroy();
    }
}
