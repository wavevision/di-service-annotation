<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class DIService
{

	/**
	 * @var array<string>
	 */
	public $params = [];

	/**
	 * @var bool
	 */
	public $generateInject;

	/**
	 * @var bool
	 */
	public $generateFactory;
}
