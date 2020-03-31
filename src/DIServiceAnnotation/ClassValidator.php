<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use ReflectionClass;

class ClassValidator
{

	use SmartObject;

	public function validate(string $class): int
	{
		new ReflectionClass($class);
		return 0;
	}

}
