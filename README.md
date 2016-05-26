#  Instrument

[![Latest Version](https://img.shields.io/packagist/v/tuupola/instrument.svg?style=flat-square)](https://packagist.org/packages/tuupola/instrument)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/tuupola/instrument/master.svg?style=flat-square)](https://travis-ci.org/tuupola/instrument)
[![HHVM Status](https://img.shields.io/hhvm/tuupola/instrument.svg?style=flat-square)](http://hhvm.h4cc.de/package/tuupola/instrument)
[![Coverage](http://img.shields.io/codecov/c/github/tuupola/instrument.svg?style=flat-square)](https://codecov.io/github/tuupola/instrument)


With instrument you can easily write PHP application metrics to an [InfluxDB](https://influxdata.com/) database. Example [Grafana](http://grafana.org/) dashboard included.


![Instrument](http://www.appelsiini.net/img/instrument-headline-1400.png)


## Setup

Install using [composer](https://getcomposer.org/).

``` bash
$ composer require tuupola/instrument
```

After installing connect Instrument to your database and start sending data.

``` php
require __DIR__ . "/vendor/autoload.php";

$influxdb = InfluxDB\Client::fromDSN("http+influxdb://user:pass@localhost:8086/instrument");
$instrument = new Instrument\Instrument([
    "adapter" => new Instrument\Adapter\InfluxDB($influxdb),
    "transformer" => new Instrument\Transformer\InfluxDB
]);

$instrument->count("users", 100);

$instrument->send();
```

## Demo

To see Instrument in action start the Vagrant demo server and make some example requests.

``` bash
$ cd demo
$ vagrant up
$ while sleep 1; do curl http://192.168.50.53/random; done
```

The above commands start the server and inserts random Instrument data every second.
You can now access the provided [demo dashboard](http://192.168.50.53:3000/dashboard/db/instrument) (admin:admin) to see this happening live.

![Grafana](http://www.appelsiini.net/img/instrument-grafana-1400-2.png)

## Concept

Documentation assumes you have working knowledge of [InlfuxDB data structures](https://docs.influxdata.com/influxdb/v0.13/concepts/key_concepts/). Each measurement must have a `name`. Measurements should contain either one `value` or several value `fields` or both. Optionally measurement can have one or more `tags`.

For example to create a new `count` measurement with name `users` with one value of `100` use either of the following.

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

```php
$requests = $instrument->count("requests", 50); /* 50 */
$requests->increment(); /* 51 */
$requests->decrement(); /* 50 */
$requests->increment(5); /* 55 */
```

Or if you prefer fluent interfaces you can also do the following.

```php
$instrument
  ->count("users")
  ->set("active", 27) /* 27 */
  ->increase("active", 5) /* 32 */
  ->decrease("active", 2); /* 30 */
```

### Timing

With timing you can measure execution time in milliseconds. You can either pass the value yourself or use the provided helpers to measure code execution time.

```php
$instrument->timing("roundtrip")->set("firstbyte", 28);
$instrument->timing("roundtrip")->set("lastbyte", 40);

$instrument->timing("roundtrip")->set("processing", function () {
    /* Code to be measured */
});

$instrument->timing("roundtrip")->start("fetching");
/* Code to be measured */
$instrument->timing("roundtrip")->stop("fetching");
```

Since timing internally uses [symfony/stopwatch](https://github.com/symfony/stopwatch) you can get PHP memory usage as a bonus. It is not automatically included in the measurement data, but you can include it manually.

```php
$memory = $instrument->timing("roundtrip")->memory()
$instrument->timing("roundtrip")->set("memory", $memory);
```

### Gauge

Gauge is same as count. However it remembers the value between requests. Gauge values are zeroed when server restarts. You need the [shmop extension](http://php.net/manual/en/book.shmop.php) and [klaussilveira/simple-shm](https://github.com/klaussilveira/SimpleSHM/) to be able to use gauges.

```bash
composer require klaussilveira/simple-shm
```

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
