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
    public function setValue($key, $value = null)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        if (null === $value) {
            $value = $key;
            $key = "value";
        }
        $this->value[$key] = $value;
        return $this;
    }

    /** @deprecated */
    public function getValue($key = "value")
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        if (isset($this->value[$key])) {
            return $this->value[$key];
        }
        return null;
    }

    public function fields()
    {
        $fields = $this->value;
        unset($fields["value"]);
        return $fields;
    }

    /** @deprecated */
    public function getFields()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $fields = $this->value;
        unset($fields["value"]);
        return $fields;
    }

    public function tags($tags = null) {
        if (null === $tags) {
            return $this->tags;
        }
        $this->tags = $tags;
        return $this;
    }

    /** @deprecated */
    public function setTags(array $tags)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->tags = $tags;
        return $this;
    }

    /** @deprecated */
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
    public function setName($name)
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        $this->name = (string) $name;
        return $this;
    }

    /** @deprecated */
    public function getName()
    {
        trigger_error("Method " . __METHOD__ . " is deprecated", E_USER_DEPRECATED);
        return $this->name;
    }

    abstract public function getType();
}
