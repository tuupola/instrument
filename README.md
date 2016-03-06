# [WIP] Application metrix toolbox for InfluxDB

[![Latest Version](https://img.shields.io/github/release/tuupola/instrument.svg?style=flat-square)](https://github.com/tuupola/instrument/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/tuupola/instrument/master.svg?style=flat-square)](https://travis-ci.org/tuupola/instrument)
[![HHVM Status](https://img.shields.io/hhvm/tuupola/instrument.svg?style=flat-square)](http://hhvm.h4cc.de/package/tuupola/instrument)
[![Coverage](http://img.shields.io/codecov/c/github/tuupola/instrument.svg?style=flat-square)](https://codecov.io/github/tuupola/instrument)

## Install

Install using [composer](https://getcomposer.org/).

``` bash
$ composer require tuupola/instrument
```

## Quickstart

Connect Instrument to your database and start sending data.

``` php
$influxdb = new InfluxDB\Client("localhost", 8086);
$instrument = new Instrument\Instrument([
    "adapter" => new Instrument\Adapter\InfluxDB($influxdb),
    "transformer" => new Instrument\Transformer\InfluxDB
]);

$instrument->count("users", 100);

$instrument->send();
```

## Concept

Documentation assumes you have working knowledge of [InlfuxDB data structures](https://docs.influxdata.com/influxdb/v0.10/concepts/key_concepts/). Each measurement must have a `name`. Measurements should contain either one `value` or several value `fields` or both. Optionally measurement can have one or more `tags`.

For example to create a new `count` measurement with name `users` with one value of `10` use either of the following.

``` php
$instrument->count("users", 100);
$instrument->count("users")->set(100);
```

````
> SELECT * FROM users
name: users
---------
time                  value
1457067288109133121	  100
```

To log several values and additionally tag the measurement.

``` php
$instrument
  ->count("users")
  ->set("total", 100)
  ->set("active", 27)
  ->tags(["host" => "localhost"]);
```

````
> SELECT * FROM users
name: users
---------
time                  total   active  host
1457067288109134122	  100     27      localhost
```

## Datatypes
### Count

Count is the simplest datatype. In addition to setting the value you can also increment and decrement it.

``` php
$requests = $instrument->count("requests", 50); /* 50 */
$requests->increment(); /* 51 */
$requests->decrement(); /* 50 */
$requests->increment(5); /* 55 */

$instrument
  ->count("users")
  ->set("active", 27) /* 27 */
  ->increase("active", 5) /* 32 */
  ->decrease("active", 2); /* 30 */
```

### Timing

With timing you can measure execution time in milliseconds. You can either pass the value yourself or use the provided helpers.

```php
$instrument->timing("roundtrip")->set("firstbyte", 28);
$instrument->timing("roundtrip")->set("lastbyte", 40);

$instrument->timing("roundtrip")->set("processing", function () {
    /* Here be dragons */
});

$instrument->timing("roundtrip")->start("sleep");
/* Here be dragons */
$instrument->timing("roundtrip")->stop("sleep");
```

Since timing internally uses [symfony/stopwatch](https://github.com/symfony/stopwatch) you can get PHP memory usage as a bonus. It is not automatically included in the measurement data, but you can include it manually.

```php
$memory = $instrument->timing("roundtrip")->memory()
$memory = $instrument->timing("roundtrip")->set("memory", $memory);
```

### Gauge

Gauge is same as count. However it remembers the value between requests. You need the [shmop extension](http://php.net/manual/en/book.shmop.php) to be able to use gauges.

## Testing

You can run tests either manually...

``` bash
$ vendor/bin/phpunit
$ vendor/bin/phpcs --standard=PSR2 src/ -p
```

... or automatically on every code change.

``` bash
$ npm install
$ grunt watch
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email tuupola@appelsiini.net instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
