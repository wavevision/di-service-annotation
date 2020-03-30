<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use ReflectionClass;

require_once $argv[1];

class ClassValidator
{

	use SmartObject;

	public function validate(string $class): int
	{
		new ReflectionClass($class);
		return 0;
	}

}

exit((new ClassValidator())->validate($argv[2]));