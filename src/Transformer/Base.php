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
namespace Instrument\Transformer;

use Instrument\Transformer;

abstract class Base implements Transformer
{
    use \Witchcraft\Hydrate;

    private $points = [];

    public function __construct($options = [])
    {
        $this->hydrate($options);
    }

    abstract public function transform(array $measurements);
}
