<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\Runner;

Runner::run(
	new Configuration(
		__DIR__ . '/../tests/DIServiceAnnotationTests/Services',
		__DIR__ . '/../temp/test.neon'
	)
);