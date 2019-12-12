<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

class DefaultGenerator
{

	protected string $mask;

	protected string $template;

	public function __construct(string $mask, string $template)
	{
		$this->mask = $mask;
		$this->template = $template;
	}

}
