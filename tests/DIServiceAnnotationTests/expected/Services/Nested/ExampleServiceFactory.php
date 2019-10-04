<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Nested;

interface ExampleServiceFactory
{

	public function create(): ExampleService;
}
