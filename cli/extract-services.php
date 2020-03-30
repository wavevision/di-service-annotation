<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
require_once $autoload;

use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\Runner;

Runner::run(
	(new Configuration(
		__DIR__ . '/../tests/DIServiceAnnotationTests/Services',
		__DIR__ . '/../temp/test.neon'
	))->setAutoloadFile($autoload)->setTempDir(__DIR__ . '/../temp')
);