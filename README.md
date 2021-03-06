[![Downloads this Month](https://img.shields.io/packagist/dm/achse/php-math-interval.svg)](https://packagist.org/packages/achse/php-math-interval)
[![Latest Stable Version](https://poser.pugx.org/achse/php-math-interval/v/stable)](https://github.com/achse/php-math-interval/releases)
[![Build Status](https://travis-ci.org/Achse/php-math-interval.svg?branch=master)](https://travis-ci.org/Achse/php-math-interval)
[![Scrutinizer](https://scrutinizer-ci.com/g/Achse/php-math-interval/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Achse/php-math-interval/)
[![Code Climate](https://codeclimate.com/github/Achse/php-math-interval/badges/gpa.svg)](https://codeclimate.com/github/Achse/php-math-interval)
[![Coverage Status](https://coveralls.io/repos/github/Achse/php-math-interval/badge.svg?branch=master)](https://coveralls.io/github/Achse/php-math-interval?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/59463ab16725bd00533b7d31/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/59463ab16725bd00533b7d31)

## What it does?

Filters, calendars, opening hours, activity logs, ... all where since-till appears you can find intervals. Many times it looks like this:
```php
public function foo(DateTimeImmutable $sice, DateTimeImmutable $till) { ... }
```
In such an implementation you have to repeat chceck `$since > $till` all over again. Also you have to write your own tooling to work with intervals.

Library `php-math-interval` brings **Value Objects** for representation and powerful tooling to easy manipulation.

```php
public function foo(DateTimeImmutableInterval $interval) { ... }
```

## It's awesome!

* Heavily **tested**,
* all object are **immutable**,
* code is **clean and predictable**,
* interval is modeled as **mathematical entity**.

```
composer require achse/php-math-interval
```

## Create an interval
Via factories (most simple):
```php
$interval = DateTimeImmutableIntervalFactory::create(
	new \DateTimeImmutable('2015-10-07T12:00:00+02:00'), 
	Boundary::CLOSED, 
	new \DateTimeImmutable('2015-10-07T14:00:00+02:00'), 
	Boundary::OPENED
);
echo (string)$interval; // [2015-10-07T12:00:00+02:00, 2015-10-07T14:00:00+02:00)
```

Directly via constructors:
```php
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable; // We need object implementing IComparable

$left = new IntegerBoundary(new DateTimeImmutable('2015-10-07T12:00:00+02:00'), Boundary::CLOSED);
$right = new IntegerBoundary(new DateTimeImmutable('2015-10-07T14:00:00+02:00'), Boundary::OPENED);
$interval = new DateTimeImmutableInterval($left, $right);
```

Parsed from string (used for tests mostly):
```php
$interval = DateTimeImmutableIntervalStringParser::parse('[2015-01-01T05:00:00+02:00, 2015-01-01T10:00:00+02:00)');
```

## Methods
Interval object provides powerful tooling for operations with intervals:

```php
use Achse\Math\Interval\Integer\IntegerIntervalStringParser as Parser;
```

* Test if interval contains element
```php
$interval = Parser::parse('[1, 2]');
$interval->isContainingElement(new Integer(2)); // true
$interval->isContainingElement(new Integer(3)); // false
```
* Get intersection between two intervals
```php
// (1, 3) ∩ (2, 4) ⟺ (2, 3)
Parser::parse('(1, 3)')->intersection(Parser::parse('(2, 4)')); // (2, 3)
```

* Get union of two intervals
```php
// Union of overlapping intervals
Parser::parse('[1, 3]')->union(Parser::parse('[2, 4]')); // ['[1, 4]']
// Union of not overlapping intervals
Parser::parse('[1, 2)')->union(Parser::parse('[3, 4]')); // ['[1, 4]']
// Union of two distant intervals is array of those two intervals 
Parser::parse('[1, 2]')->union(Parser::parse('[3, 4]')); // ['[1, 2], [3, 4]']
```

* Diff two intervals
```php
// [1, 4] \ [2, 3]
Parser::parse('[1, 4]')->difference(Parser::parse('[2, 3]')); // ['[1,2)', '(3, 4]']
```

* Test if one interval contains the other
```php
// [1, 4] contains [2, 3]
Parser::parse('[1, 4]')->isContaining(Parser::parse('[2, 3]')); // true
// [2, 3] NOT contains [1, 4]
Parser::parse('[2, 3]')->isContaining(Parser::parse('[1, 4]')); // false
```

* Does one interval overlap other one from right
```php
Parser::parse('[1, 2]')->isOverlappedFromRightBy(Parser::parse('[2, 3]')); // true
Parser::parse('[2, 3]')->isOverlappedFromRightBy(Parser::parse('[1, 2]')); // false
// (1, 2) ~ [2, 3]
Parser::parse('(1, 2)')->isOverlappedFromRightBy(Parser::parse('[2, 3]')); // false
```

* Test if two intervals collides (not empty intersection)
```php
Parser::parse('[2, 3]')->isColliding(Parser::parse('[1, 2]')); // true
Parser::parse('[1, 2]')->isColliding(Parser::parse('(2, 3)')); // false
```

* `isFollowedByWithPrecision` and `isFollowedByAtMidnight` is available for testing continuity of `DateTimeImmutable` / `DateTime` intervals between days.

## Available Types
Library contains intervals for those types:
* `Integer` - classic int,
* `DateTimeImmutable` and `DateTime` (I strongly advise you to use Immutable only),
* `SingeDayTime` - represents "clock-time" of one day. From *[00:00:00* one day to *00:00:00)* another.

## Creating your own type
`Interval` (its `Boundary`) can contains any type that implements `IComparable`, but if you want
to have type-hinting you may want to write your own `XyInterval` and `XyBoundary` class 
and probably also `Factory` classes.
