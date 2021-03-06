<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalStringParser;



/**
 * @deprecated Use DateTimeImmutable, always!
 */
final class DateTimeIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return DateTimeInterval
	 */
	public static function parse(string $string): Interval
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new DateTimeInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return DateTimeBoundary
	 */
	protected static function parseBoundary(string $input): Boundary
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($input);

		/** @var DateTime $dateTime */
		$dateTime = DateTime::createFromFormat(\DateTime::ATOM, $elementString);

		return new DateTimeBoundary(DateTime::from($dateTime), $state);
	}

}
