<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class DIService
{

	/**
	 * @var bool
	 */
	public $inject = true;

	/**
	 * @var string[]
	 */
	public $params = [];

	/**
	 * @var string[]
	 */
	public $tags = [];

	/**
	 * @var bool
	 */
	public $generateInject;

	/**
	 * @var bool
	 */
	public $generateFactory;

	/**
	 * @var bool
	 */
	public $generateComponent;

}
