<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class DIService
{

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var bool
	 */
	public $enableInject = true;

	/**
	 * @var bool
	 */
	public $generateComponent;

	/**
	 * @var bool
	 */
	public $generateFactory;

	/**
	 * @var bool
	 */
	public $generateInject;

	/**
	 * @var string[]
	 */
	public $params = [];

	/**
	 * @var string[]
	 */
	public $tags = [];

}
