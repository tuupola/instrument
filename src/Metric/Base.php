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

abstract class Base implements Metric
{
    private $name = null;
    protected $value = [];
    private $tags = [];

    public function __construct($options = [])
    {
        if (isset($options["name"])) {
            $this->name($options["name"]);
            unset($options["name"]);
        }

        foreach ($options as $key => $value) {
            if (null !== $value) {
                $this->set($key, $value);
            }
        }
    }

    public function name($name = null) {
        if (null === $name) {
            return $this->name;
        }
        $this->name = $name;
        return $this;
    }

    public function set($key, $value = null)
    {
        if (null === $value) {
            $value = $key;
            $key = "value";
        }
        $this->value[$key] = $value;
        return $this;
    }

    public function get($key = "value")
    {
        if (isset($this->value[$key])) {
            return $this->value[$key];
        }
        return null;
    }

    public function value($key = "value", $value = null)
    {
        if (null === $value) {
            if (isset($this->value[$key])) {
                return $this->value[$key];
            }
            return null;
        }
        $this->value[$key] = $value;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setValue($key, $value = null)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->set($key, $value);

    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getValue($key = "value")
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->get($key);
    }

    public function fields()
    {
        $fields = $this->value;
        unset($fields["value"]);
        return $fields;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getFields()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->fields();
    }

    public function tags(array $tags = null) {
        if (null === $tags) {
            return $this->tags;
        }
        $this->tags = $tags;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setTags(array $tags)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->tags = $tags;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getTags()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->tags;
    }

    public function addTag($key, $value)
    {
        $this->tags[$key] = $value;
        return $this;
    }

    public function addTags(array $tags)
    {
        $this->tags = array_merge($this->tags, $tags);
        return $this;
    }

    public function removeTag($key)
    {
        unset($this->tags[$key]);
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function setName($name)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->name = (string) $name;
        return $this;
    }

    /** @deprecated */
    /** @codeCoverageIgnore */
    public function getName()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->name;
    }

    abstract public function getType();
}
