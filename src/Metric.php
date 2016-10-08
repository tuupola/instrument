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

namespace Instrument;

interface Metric
{
    public function setValue($value);
    public function getValue();
    public function set($key, $value);
    public function get($key);

    public function setName($name);
    public function getName();

    public function getType();

    public function setTags(array $tags);
    public function getTags();
    public function addTag($key, $value);
    public function addTags(array $tags);
    public function removeTag($key);
}
