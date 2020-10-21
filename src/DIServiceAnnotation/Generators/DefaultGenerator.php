<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation\Generators;

use Nette\Utils\FileSystem;
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\Utils\Path;
use function file_put_contents;
use function is_file;
use function sprintf;

class DefaultGenerator
{

	protected Configuration $configuration;

	protected string $mask;

	protected string $template;

	public function __construct(Configuration $configuration, string $mask, string $template)
	{
		$this->configuration = $configuration;
		$this->mask = $mask;
		$this->template = $template;
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

	/**
	 * @param array<mixed> $params
	 */
	protected function renderTemplate(string $output, array $params): void
	{
		if ($this->configuration->getRegenerate() || !is_file($output)) {
			file_put_contents($output, sprintf(FileSystem::read($this->template), ...$params));
			$this->configuration->getOutput()->writeln("Generating: " . Path::realpath($output));
		}
	}

}
