<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services;

trait InjectSimple
{

	protected Simple $simple;

	public function injectSimple(Simple $simple): void
	{
		$this->simple = $simple;
	}

}
