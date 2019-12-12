<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;

class DefaultInject extends DefaultGenerator implements Inject
{

	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file): void
	{
		$namespace = $reflectionClass->getNamespaceName();
		$name = $reflectionClass->getShortName();
		$injectName = sprintf($this->mask, $name);
		$outputFile = dirname($file->getPathname()) . "/$injectName.php";
		if (!is_file($outputFile)) {
			Helpers::renderTemplate(
				$this->template,
				$outputFile,
				[
					$namespace,
					$injectName,
					$name,
					lcfirst($injectName),
					lcfirst($name),
				]
			);
		}
	}

}
