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

    public function testShouldAddAndRemoteTags()
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
        usleep(2500);
        $timing->stop();

        $timing->start("jump");
        usleep(3500);
        $timing->stop("jump");

        $this->assertTrue($timing->get() >= 2);
        $this->assertTrue($timing->get("jump") >= 3);
    }

    public function testShouldStartAndStopMultipleTimes()
    {
        $timing = new Timing();
        $timing->start("multipass");
        usleep(10000);
        $timing->stop("multipass");
        $this->assertTrue($timing->get("multipass") >= 10);

        sleep(1);

        $timing->start("multipass");
        usleep(10000);
        $timing->stop("multipass");
        $this->assertTrue($timing->get("multipass") >= 20);

        sleep(1);

        $timing->start("multipass");
        usleep(10000);
        $timing->stop("multipass");
        $this->assertTrue($timing->get("multipass") >= 30);
    }

    public function testShouldMeasureClosure()
    {
        $timing = new Timing();
        $timing->closure(function () {
            usleep(2500);
        });
        $timing->closure("dive", function () {
            usleep(3500);
        });

        $this->assertTrue($timing->get() >= 2);
        $this->assertTrue($timing->get("dive") >= 3);
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
}
