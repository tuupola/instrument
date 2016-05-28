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

class TimingTest extends \PHPUnit_Framework_TestCase
{
    const DELTA = 10;

    public function testShouldSetAndGetValue()
    {
        $timing = new Timing();
        $timing->set(521);
        $timing->set("roundtrip", 8923);

        $this->assertEquals(521, $timing->get());
        $this->assertEquals(521, $timing->get("value"));
        $this->assertEquals(8923, $timing->get("roundtrip"));
        $this->assertEquals(null, $timing->get("nosuch"));
    }

    public function testShouldGetFields()
    {
        $timing = new Timing();
        $timing->set(10);
        $timing->set("users", 300);
        $timing->set("logins", 500);
        $this->assertEquals(["users" => 300, "logins" => 500], $timing->fields());
    }

    public function testShouldSetAndGetTags()
    {
        $timing = new Timing();
        $timing->setTags(["foo" => "bar"]);
        $this->assertEquals(["foo" => "bar"], $timing->getTags());
    }

    public function testShouldAddAndRemoveTags()
    {
        $timing = new Timing();
        $timing->setTags(["foo" => "bar"]);
        $this->assertEquals(["foo" => "bar"], $timing->getTags());
        $timing->addTag("hit", "pop");
        $this->assertEquals(["foo" => "bar", "hit" => "pop"], $timing->getTags());
        $timing->removeTag("foo");
        $this->assertEquals(["hit" => "pop"], $timing->getTags());
    }

    public function testShouldBeChainable()
    {
        $timing = new Timing();
        $timing->set(521)->name("sprint")->set("roundtrip", 8923);

        $this->assertEquals("sprint", $timing->name());
        $this->assertEquals(521, $timing->get());
        $this->assertEquals(8923, $timing->get("roundtrip"));
    }

    public function testShouldStartAndStop()
    {
        $timing = new Timing();
        $timing->start();
        usleep(20000);
        $timing->stop();

        $timing->start("jump");
        usleep(30000);
        $timing->stop("jump");

        $this->assertEquals($timing->get(), 20, null, self::DELTA);
        $this->assertEquals($timing->get("jump"), 30, null, self::DELTA);
    }

    public function testShouldStartAndStopMultipleTimes()
    {
        $timing = new Timing();
        $timing->start("multipass");
        usleep(10000);
        $timing->stop("multipass");
        $this->assertEquals($timing->get("multipass"), 10, null, self::DELTA);

        sleep(1);

        $timing->start("multipass");
        usleep(10000);
        $timing->stop("multipass");
        $this->assertEquals($timing->get("multipass"), 20, null, self::DELTA);

        sleep(1);

        $timing->start("multipass");
        usleep(10000);
        $timing->stop("multipass");
        $this->assertEquals($timing->get("multipass"), 30, null, self::DELTA);
    }

    public function testShouldMeasureClosure()
    {
        $timing = new Timing();
        $timing->set(function () {
            usleep(20000);
        });
        $timing->closure("dive", function () {
            usleep(30000);
        });
        $timing->set("fly", function () {
            usleep(15000);
        });

        $this->assertEquals($timing->get(), 20, null, self::DELTA);
        $this->assertEquals($timing->get("dive"), 30, null, self::DELTA);
        $this->assertEquals($timing->get("fly"), 15, null, self::DELTA);
    }

    public function testClosureShouldReturn()
    {
        $timing = new Timing();
        $return = $timing->closure(function () {
            return "Suits you sir!";
        });

        $this->assertEquals("Suits you sir!", $return);
    }

    public function testShouldReturnType()
    {
        $timing = new Timing(["name" => "sprint"]);
        $this->assertEquals("timing", $timing->type());
    }

    public function testShouldSetAndGetStopWatch()
    {
        $timing = new Timing(["name" => "sprint"]);
        $this->assertInstanceOf("Symfony\Component\Stopwatch\Stopwatch", $timing->getStopwatch());
    }

    public function testShouldBeAbleToGetMemory()
    {
        $timing = new Timing;
        $timing->set(function () {
            usleep(10000);
        });

        $this->assertEquals($timing->get(), 10, null, self::DELTA);
        $this->assertGreaterThan(0, $timing->memory());
    }

    public function testShouldNotThrowWhenNotStarted()
    {
        $timing = new Timing;
        $timing->stop("nosuch");

        $this->assertNull($timing->get());
        $this->assertNull($timing->memory());
    }

    public function testShouldStopAllTimers()
    {
        $timing = new Timing;
        $timing->start("first");
        usleep(10000);
        $timing->start("second");
        usleep(10000);

        $this->assertNull($timing->get("first"));
        $this->assertNull($timing->get("second"));

        $timing->stopAll();

        $this->assertEquals($timing->get("first"), 20, null, self::DELTA);
        $this->assertEquals($timing->get("second"), 10, null, self::DELTA);
    }
}
