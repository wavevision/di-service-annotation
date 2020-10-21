<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;
use Wavevision\Utils\Arrays;
use function dirname;
use function explode;
use function lcfirst;
use function sprintf;

class DefaultComponent extends DefaultGenerator implements Component
{

	/**
	 * @inheritDoc
	 */
	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file, string $originalName): void
	{
		$namespace = $reflectionClass->getNamespaceName();
		$componentName = Arrays::lastItem(explode('\\', $namespace));
		$maskedComponentName = sprintf($this->mask, $componentName);
		$type = $reflectionClass->getShortName();
		$filename = dirname($file->getPathname()) . "/$maskedComponentName.php";
		$this->renderTemplate(
			$filename,
			[
				$namespace,
				$maskedComponentName,
				$type,
				lcfirst($type),
				lcfirst($componentName . $type),
				$componentName . $type,
				$componentName,
				$originalName,
				lcfirst($componentName),
			]
		);
	}

}
