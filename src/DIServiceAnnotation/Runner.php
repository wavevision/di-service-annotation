<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Nette\StaticClass;

final class Runner
{

	use StaticClass;

	public static function run(Configuration ...$configurations): void
	{
		AnnotationRegistry::registerLoader('class_exists');
		foreach ($configurations as $configuration) {
			(new ExtractServices($configuration))->run();
		}
	}

}
