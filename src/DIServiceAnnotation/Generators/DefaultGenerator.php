<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use Nette\Utils\FileSystem;
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\Utils\Path;

class DefaultGenerator
{

	protected Configuration $configuration;

	protected string $mask;

	protected string $template;

	private bool $regenerate;

	public function __construct(Configuration $configuration, string $mask, string $template, bool $regenerate)
	{
		$this->configuration = $configuration;
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

	/**
	 * @param array<mixed> $params
	 */
	protected function renderTemplate(string $output, array $params): void
	{
		if ($this->regenerate || !is_file($output)) {
			file_put_contents($output, sprintf(FileSystem::read($this->template), ...$params));
			$this->configuration->getOutput()->writeln("Generating: " . Path::realpath($output));
		}
	}

}
