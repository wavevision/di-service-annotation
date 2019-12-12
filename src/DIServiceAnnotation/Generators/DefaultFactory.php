<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;

class DefaultFactory extends DefaultGenerator implements Factory
{

	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file): string
	{
		$name = $reflectionClass->getShortName();
		$namespace = $reflectionClass->getNamespaceName();
		$factoryName = sprintf($this->mask, $name);
		$filename = dirname($file->getPathname()) . "/$factoryName.php";
		if ($this->shouldGenerate($filename)) {
			Helpers::renderTemplate(
				$this->template,
				$filename,
				[
					$namespace,
					$factoryName,
					$name,
				]
			);
			require_once $filename;
		}
		$fullFactoryName = $namespace . '\\' . $factoryName;
		return $fullFactoryName;
	}

}
