<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;
use function dirname;
use function lcfirst;
use function sprintf;

class DefaultInject extends DefaultGenerator implements Inject
{

	/**
	 * @inheritDoc
	 */
	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file): void
	{
		$namespace = $reflectionClass->getNamespaceName();
		$name = $reflectionClass->getShortName();
		$injectName = sprintf($this->mask, $name);
		$filename = dirname($file->getPathname()) . "/$injectName.php";
		$this->renderTemplate(
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
