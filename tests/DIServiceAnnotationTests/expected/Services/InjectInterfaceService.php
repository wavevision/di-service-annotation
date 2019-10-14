<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services;

trait InjectInterfaceService
{

	/**
	 * @var InterfaceService
	 */
	private $interfaceService;

	public function injectInterfaceService(InterfaceService $interfaceService): void
	{
		$this->interfaceService = $interfaceService;
	}

}
