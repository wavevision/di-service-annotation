<?php declare(strict_types = 1);

use Wavevision\DIServiceAnnotation\ClassValidator;

require_once $argv[1];
exit((new ClassValidator())->validate($argv[2]));
