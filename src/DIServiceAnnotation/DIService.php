<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

//phpcs:disable SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable

/**
 * @Annotation
 * @Target("CLASS")
 */
final class DIService
{

	public ?string $name = null;

	public bool $enableInject = true;

	public bool $generateComponent = false;

	public bool $generateFactory = false;

	public bool $generateInject = false;

	/**
	 * @var string[]
	 */
	public array $params = [];

	/**
	 * @var string[]
	 */
	public array $tags = [];

}
