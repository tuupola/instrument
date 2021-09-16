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
