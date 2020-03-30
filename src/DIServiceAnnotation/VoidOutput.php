<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

class VoidOutput implements Output
{

	public function write(string $string): void
	{
	}

	public function writeln(string $string): void
	{
		// TODO: Implement writeln() method.
	}

}
