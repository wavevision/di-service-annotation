<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;

class VoidOutput implements Output
{

	use SmartObject;

	public function writeln(string $string): void
	{
		// intentionally blank
	}

}
