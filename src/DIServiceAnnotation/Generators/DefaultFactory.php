<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use ReflectionClass;
use SplFileInfo;
use function dirname;
use function sprintf;

class DefaultFactory extends DefaultGenerator implements Factory
{

	/**
	 * @inheritDoc
	 */
	public function generate(ReflectionClass $reflectionClass, SplFileInfo $file): string
	{
		$name = $reflectionClass->getShortName();
		$namespace = $reflectionClass->getNamespaceName();
		$factoryName = sprintf($this->mask, $name);
		$filename = dirname($file->getPathname()) . "/$factoryName.php";
		$this->renderTemplate(
			$filename,
			[
				$namespace,
				$factoryName,
				$name,
			]
		);
		require_once $filename;
		/** @var class-string<object> $className */
		$className = $namespace . '\\' . $factoryName;
		return $className;
	}

}
