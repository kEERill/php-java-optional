# keerill/php-java-optional

Full implementation of JAVA8 Optional for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/keerill/php-java-optional.svg?style=flat-square)](https://packagist.org/packages/keerill/php-java-optional)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/keerill/php-java-optional/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/keerill/php-java-optional/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/keerill/php-java-optional.svg?style=flat-square)](https://packagist.org/packages/keerill/php-java-optional)

## Usage

```php
// ofEmpty refers Optional#empty() in java
// It is renamed as ofEmpty() because of empty() is reserved by PHP 
Optional::ofEmpty()
    ->isPresent(); // false

Optional::of('value')
    ->orElse('elseValue'); // value
 
Optional::ofEmpty()
    ->orElseThrow(fn () => new InvalidArgumentException()); // throws exception

Optional::ofEmpty()
    ->filter(fn ($a) => (int) $a); // function is not executed

Optional::of(5)
    ->map(fn ($a) => $a * 2)
    ->get(); // returns 10

Optional::ofEmpty()
    ->orElseGet(fn () => 10); // returns 10
```

## Installation

```bash
composer require keerill/php-java-optional
```

## Resources

* [Java 8 Optional Documentation](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html)
* [Java 8 Optional Usage](http://www.oracle.com/technetwork/articles/java/java8-optional-2175753.html)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [kEERIll](https://github.com/kEERill)
- [serhatozdal](https://github.com/serhatozdal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
