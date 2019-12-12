<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Nested;

trait InjectExampleServiceFactory
{

	protected ExampleServiceFactory $exampleServiceFactory;

	public function injectExampleServiceFactory(ExampleServiceFactory $exampleServiceFactory): void
	{
		$this->exampleServiceFactory = $exampleServiceFactory;
	}

}
