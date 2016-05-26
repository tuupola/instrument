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

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldSetAndGetTitle()
    {
        $event = new Event();
        $event->set("test event");

        $this->assertEquals("test event", $event->get());
        $this->assertEquals("test event", $event->get("title"));

        $event->set("title", "another event");
        $this->assertEquals("another event", $event->get());
        $this->assertEquals("another event", $event->get("title"));
    }

    public function testShouldSetAndGetDescription()
    {
        $event = new Event();
        $event->set("test event");
        $event->set("description", "test description");

        $this->assertEquals("test event", $event->get("title"));
        $this->assertEquals("test description", $event->get("description"));
    }

    public function testShouldGetFields()
    {
        $event = new Event();
        $event->set("test event");
        $event->set("description", "test description");
        $this->assertEquals([
            "title" => "test event",
            "description" => "test description"
        ], $event->fields());
    }

    public function testShouldBeChainable()
    {
        $event = new Event(["name" => "events"]);
        $event->set("explosion")->set("description", "It was pretty loud.")->name("foo");

        $this->assertEquals("foo", $event->name());
        $this->assertEquals("explosion", $event->get());
        $this->assertEquals("explosion", $event->get("title"));
        $this->assertEquals("It was pretty loud.", $event->get("description"));
    }

    public function testShouldReturnType()
    {
        $event = new Event();
        $this->assertEquals("event", $event->type());
    }
}
