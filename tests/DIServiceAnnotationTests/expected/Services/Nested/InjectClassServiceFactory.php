<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Nested;

trait InjectClassServiceFactory
{

	/**
	 * @var ClassServiceFactory
	 */
	private $classServiceFactory;

	public function injectClassServiceFactory(ClassServiceFactory $classServiceFactory): void
	{
		$this->classServiceFactory = $classServiceFactory;
	}
}
