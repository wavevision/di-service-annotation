<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Nested;

use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(params={"%wwwDir%"}, tags={"t1"}, generateInject=true, generateFactory=true)
 */
class ExampleService
{

}
