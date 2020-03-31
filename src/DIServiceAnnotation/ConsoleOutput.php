<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;

class ConsoleOutput implements Output
{

	use SmartObject;

	public function writeln(string $string): void
	{
		echo $string . "\n";
	}

}
