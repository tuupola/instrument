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

interface Adapter
{
    public function send(array $points = []);
    public function setClient($client = null);
    public function getClient();
    public function setMeasurements(array $measurements);
    public function getMeasurements();
}
