<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;

interface Component
{

	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file, string $originalName): void;

}
