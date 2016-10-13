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
use InfluxDB\Point;

class InfluxDBLineProtocol extends Base implements Transformer
{
    public function transform(array $measurements)
    {
        $points = [];
        foreach ($measurements as $key => $value) {
            if (is_integer($key)) {
                $key = "{$value->name()}-{$key}";
            }
            $points[$key] = (string)(new Point(
                $value->name(),
                $value->get("value"),
                $value->tags(),
                $value->fields(),
                null
            ));
        }
        return $points;
    }
}
