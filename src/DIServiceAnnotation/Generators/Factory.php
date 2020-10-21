<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;

interface Factory
{

	/**
	 * @param ReflectionClass<object> $reflectionClass
	 * @return class-string<object>
	 */
	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file): string;

}
