<?php

/*

Copyright (c) 2016-2021 Mika Tuupola

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

/**
 * @see       https://github.com/tuupola/instrument
 * @license   https://www.opensource.org/licenses/mit-license.php
 */

namespace Instrument\Metric;

use PHPUnit\Framework\TestCase;

class GaugeTest extends TestCase
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

    public function testShouldInitWhenIncreasing()
    {
        $gauge = new Gauge();

        $gauge->increase();
        $gauge->increase("users", 10);
        $this->assertEquals(1, $gauge->get());
        $this->assertEquals(10, $gauge->get("users"));
        $gauge->destroy();
    }

    public function testShouldInitWhenDecreasing()
    {
        $gauge = new Gauge();

        $gauge->decrease();
        $gauge->decrease("users", 10);
        $this->assertEquals(-1, $gauge->get());
        $this->assertEquals(-10, $gauge->get("users"));
        $gauge->destroy();
    }
}
