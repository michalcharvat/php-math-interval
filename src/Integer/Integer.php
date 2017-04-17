<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Integer;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\IntervalUtils;
use InvalidArgumentException;
use LogicException;



final class Integer implements IComparable
{

	use ComparisonMethods;

	/**
	 * @var int
	 */
	private $internal;



	/**
	 * @param int $internal
	 */
	public function __construct(int $internal)
	{
		$this->internal = $internal;
	}



	/**
	 * @param string $string
	 * @return static
	 */
	public static function fromString(string $string): Integer
	{
		if (!is_numeric($string) || (string) (int) $string !== $string) {
			throw new InvalidArgumentException("'$string' in not integer-like.");
		}

		return new static((int) $string);
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
	{
		if (!$other instanceof static) {
			throw new LogicException(
				sprintf(
					'You cannot compare sheep with the goat. Type %s expected, but %s given.',
					get_class($this),
					get_class($other)
				)
			);
		}

		return IntervalUtils::numberCmp($this->internal, $other->toInt());
	}



	/**
	 * @return int
	 */
	public function toInt(): int
	{
		return $this->internal;
	}



	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return (string) $this->toInt();
	}
}