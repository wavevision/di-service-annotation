<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Nette\StaticClass;

final class Runner
{

	use StaticClass;

	/**
	 * @param Configuration[] $configurations
	 */
	public static function run(array ...$configurations): void
	{
		AnnotationRegistry::registerLoader('class_exists');
		foreach ($configurations as $configuration) {
			(new ExtractServices($configuration))->run();
		}
	}

}
