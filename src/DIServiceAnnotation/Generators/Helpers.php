<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use Nette\StaticClass;
use Nette\Utils\FileSystem;

class Helpers
{

	use StaticClass;

	/**
	 * @param array<mixed> $params
	 */
	public static function renderTemplate(string $template, string $output, array $params): void
	{
		file_put_contents($output, sprintf(FileSystem::read($template), ...$params));
	}

}
