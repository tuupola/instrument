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

class EventTest extends TestCase
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
