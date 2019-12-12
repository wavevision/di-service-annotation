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
		$filename = dirname($file->getPathname()) . "/$injectName.php";
		if ($this->shouldGenerate($filename)) {
			Helpers::renderTemplate(
				$this->template,
				$filename,
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
