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

/* Timing for bootstrap */
$start = microtime(true);

require __DIR__ . "/vendor/autoload.php";

$app = new \Slim\App([
    "settings" => [
        "displayErrorDetails" => true
    ]
]);

$container = $app->getContainer();

$container["influxdb"] = function ($container) {
    return InfluxDB\Client::fromDSN("http+influxdb://foo:bar@localhost:8086/instrument");
};

$container["instrument"] = function ($container) {
    return new Instrument\Instrument([
        "adapter" => new Instrument\Adapter\InfluxDB($container["influxdb"]),
        "transformer" => new Instrument\Transformer\InfluxDB
    ]);
};

$app->get("/random", function ($request, $response, $arguments) {
    $timing = $this->instrument->timing("response")->tags(["host" => "localhost"]);;

    /* Emulate database queries. */
    $timing->start("database");

    $database = rand(3, 9) * 10000;
    $database += rand(1, 100) * 100;
    usleep($database);
    print "database: {$database}us ";

    $timing->stop("database");

    /* Emulate process time. */
    $timing->start("process");

    $process = rand(2, 4) * 10000;
    $process += rand(1, 100) * 100;
    usleep($process);

    /* Use some memory. */
    $dump = [];
    $random = rand(1, 4);
    for ($i = 0 ; $i < $process; ++$i) {
        $dump[] = new StdClass;
    }
    print "process: {$process}us ";

    $timing->stop("process");

    $memory = $timing->memory();
    $timing->set("memory", $memory);
    print "memory: {$memory}B ";

    if (isset($_SERVER["REQUEST_TIME_FLOAT"])) {
        $total = (integer) ((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000);
        $timing->set("total", $total);
        print "total: {$total}ms ";
    }
    print "\n";

    $this->instrument->send();
});

$app->get("/event", function ($request, $response, $arguments) {
    $event = $this->instrument->event("deploy", "New version deployed by dopevs.")->tags(["host" => "localhost"]);
    $this->instrument->send();
});

$app->run();
