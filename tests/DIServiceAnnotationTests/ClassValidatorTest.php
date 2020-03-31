<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests;

use PHPUnit\Framework\TestCase;
use Wavevision\DIServiceAnnotation\ClassValidator;

class ClassValidatorTest extends TestCase
{

	public function test(): void
	{
		$this->assertEquals(0, (new ClassValidator())->validate(self::class));
	}

}
