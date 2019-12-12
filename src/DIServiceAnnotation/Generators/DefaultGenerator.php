<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

class DefaultGenerator
{

	protected string $mask;

	protected string $template;

	private bool $regenerate;

	public function __construct(string $mask, string $template, bool $regenerate = false)
	{
		$this->mask = $mask;
		$this->template = $template;
		$this->regenerate = $regenerate;
	}

	public function getMask(): string
	{
		return $this->mask;
	}

	/**
	 * @return static
	 */
	public function setMask(string $mask)
	{
		$this->mask = $mask;
		return $this;
	}

	public function getTemplate(): string
	{
		return $this->template;
	}

	/**
	 * @return static
	 */
	public function setTemplate(string $template)
	{
		$this->template = $template;
		return $this;
	}

	public function getRegenerate(): bool
	{
		return $this->regenerate;
	}

	/**
	 * @return static
	 */
	public function setRegenerate(bool $regenerate)
	{
		$this->regenerate = $regenerate;
		return $this;
	}

	protected function shouldGenerate(string $filename): bool
	{
		return $this->regenerate || !is_file($filename);
	}

}
