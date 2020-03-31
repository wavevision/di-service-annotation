<?php declare(strict_types = 1);

$autoload = __DIR__ . '/../../vendor/autoload.php';
require_once $autoload;

use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\Runner;

Runner::run(
	(new Configuration(
		__DIR__ . '/../DIServiceAnnotationTests/Services',
		__DIR__ . '/../../temp/test.neon'
	))->enableFileValidation($autoload, __DIR__ . '/../../temp')
);
