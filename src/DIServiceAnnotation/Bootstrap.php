<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Nette\StaticClass;
use Wavevision\Utils\Path;

final class Bootstrap
{

	use StaticClass;

	/**
	 * @var string
	 */
	private static $rootDir;

	/**
	 * @param string $rootDir
	 * @param array<string, string> $mappings
	 */
	public static function boot(string $rootDir, array $mappings): void
	{
		self::$rootDir = $rootDir;
		AnnotationRegistry::registerLoader('class_exists');
		foreach ($mappings as $source => $output) {
			(new ExtractServices(new Configuration(self::fromRoot($source), self::fromRoot($output))))->run();
		}
	}

	private static function fromRoot(string $path): string
	{
		return Path::join(self::$rootDir, $path);
	}

}
