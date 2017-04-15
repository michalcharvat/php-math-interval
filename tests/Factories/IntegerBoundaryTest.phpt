<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\Types;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\IntegerBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



class IntegerBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal('[1]', (string) IntegerBoundaryFactory::create(1, Boundary::CLOSED));
		Assert::equal('(1)', (string) IntegerBoundaryFactory::create(1, Boundary::OPENED));

		Assert::equal('(' . PHP_INT_MAX . ')', (string) IntegerBoundaryFactory::create(PHP_INT_MAX, Boundary::OPENED));
	}

}



(new IntegerBoundaryFactoryTest())->run();
