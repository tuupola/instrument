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

use Instrument\Metric;

class Count extends Base implements Metric
{
    public function increase($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }

        /* If increasing unset key init it as 0. */
        if (!isset($this->value[$key])) {
            $this->value[$key] = 0;
        }
        $this->value[$key] += $amount;
        return $this;
    }

    public function decrease($key = "value", $amount = 1)
    {
        if (is_integer($key)) {
            $amount = $key;
            $key = "value";
        }

        /* If decresing unset key init it as 0. */
        if (!isset($this->value[$key])) {
            $this->value[$key] = 0;
        }
        $this->value[$key] -= $amount;
        return $this;
    }

    public function type()
    {
        return "count";
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getType()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return "count";
    }
}
