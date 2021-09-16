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
    public function value($key = "value", $value = null);
    public function set($key, $value);
    public function get($key);
    public function name($name = null);
    public function type();
    public function fields();

    public function tags(array $tags = null);
    public function addTag($key, $value);
    public function addTags(array $tags);
    public function removeTag($key);
}
