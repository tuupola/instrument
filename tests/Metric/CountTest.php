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

use PHPUnit\Framework\TestCase;

class CountTest extends TestCase
{
    public function testShouldSetAndGetValue()
    {
        $count = new Count();
        $count->set(10);
        $count->set("users", 300);
        $this->assertEquals(10, $count->get());
        $this->assertEquals(10, $count->get("value"));
        $this->assertEquals(300, $count->get("users"));
        $this->assertEquals(null, $count->get("nosuch"));
    }

    public function testShouldGetFields()
    {
        $count = new Count();
        $count->set(10);
        $count->set("users", 300);
        $count->set("logins", 500);
        $this->assertEquals(["users" => 300, "logins" => 500], $count->fields());
    }

    public function testShouldIncreaseValue()
    {
        $count = new Count();
        $count->set(10);
        $count->set("users", 300);

        $count->increase();
        $count->increase("users");
        $this->assertEquals(11, $count->get());
        $this->assertEquals(301, $count->get("users"));

        $count->increase(10);
        $count->increase("users", 10);
        $this->assertEquals(21, $count->get());
        $this->assertEquals(311, $count->get("users"));
    }

    public function testShouldDecreaseValue()
    {
        $count = new Count();
        $count->set(10);
        $count->set("users", 300);

        $count->decrease();
        $count->decrease("users");
        $this->assertEquals(9, $count->get());
        $this->assertEquals(299, $count->get("users"));

        $count->decrease(4);
        $count->decrease("users", 10);
        $this->assertEquals(5, $count->get());
        $this->assertEquals(289, $count->get("users"));
    }

    public function testShouldBeChainable()
    {
        $count = new Count();
        $count->set(10)->increase(5)->decrease(2)->name("test");
        $count->set("users", 300)->increase("users", 5)->decrease("users", 100);

        $this->assertEquals("test", $count->name());
        $this->assertEquals(13, $count->get());
        $this->assertEquals(205, $count->get("users"));
    }

    public function testShouldReturnType()
    {
        $count = new Count();
        $this->assertEquals("count", $count->type());
    }

    public function testShouldInitWhenIncreasing()
    {
        $count = new Count();

        $count->increase();
        $count->increase("users", 10);
        $this->assertEquals(1, $count->get());
        $this->assertEquals(10, $count->get("users"));
    }

    public function testShouldInitWhenDecreasing()
    {
        $count = new Count();

        $count->decrease();
        $count->decrease("users", 10);
        $this->assertEquals(-1, $count->get());
        $this->assertEquals(-10, $count->get("users"));
    }
}
