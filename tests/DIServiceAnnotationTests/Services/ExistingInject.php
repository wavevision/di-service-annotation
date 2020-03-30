<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services;

use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(generateInject=true)
 */
class ExistingInject
{

	use SmartObject;
}
