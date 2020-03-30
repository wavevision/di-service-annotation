<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services;

use Nette\SmartObject;

class SyntaxError
{

	use SmartObject;

	public function fuckSyntax();
}