<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services;

use Wavevision\DIServiceAnnotation\DIService;

/**
 * @DIService(params={"%wwwDir%", "@name"}, generateInject=true)
 */
interface InterfaceService
{

}