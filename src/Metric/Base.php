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
    use \Witchcraft\Hydrate;
    use \Witchcraft\MagicMethods;

    private $name = null;
    protected $value = [];
    private $tags = [];

    public function __construct($options = [])
    {
        if (isset($options["name"])) {
            $this->setName($options["name"]);
            unset($options["name"]);
        }

        foreach ($options as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function set($key, $value = null)
    {
        return $this->setValue($key, $value);
    }

    public function get($key = "value")
    {
        return $this->getValue($key);
    }

    public function setValue($key, $value = null)
    {
        if (null === $value) {
            $value = $key;
            $key = "value";
        }
        $this->value[$key] = $value;
        return $this;
    }

    public function getValue($key = "value")
    {
        if (isset($this->value[$key])) {
            return $this->value[$key];
        }
        return null;
    }

    public function getFields()
    {
        $fields = $this->value;
        unset($fields["value"]);
        return $fields;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    abstract public function getType();
}
