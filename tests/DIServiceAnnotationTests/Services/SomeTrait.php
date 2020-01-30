<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services;

trait SomeTrait
{

	public function f1(): void
	{
		Simple::class;
	}

	public function f2(): void
	{
		Simple::class;
	}

}
