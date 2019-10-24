<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\Runner;

class RunnerTest extends TestCase
{

	public function testRunWithNoServices(): void
	{
		vfsStream::setup('r');
		$dir = vfsStream::url('r/d');
		mkdir($dir);
		$file = vfsStream::url('r/services.neon');
		$configuration = new Configuration($dir, $file);
		Runner::run($configuration, $configuration);
		$this->assertFileNotExists($file);
	}

}
