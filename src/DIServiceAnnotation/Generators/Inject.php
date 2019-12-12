<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;

interface Inject
{

	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file): void;

}
