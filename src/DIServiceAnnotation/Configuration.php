<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;

final class Configuration
{

	use SmartObject;

	/**
	 * Where to find DIService annotation
	 * @var string
	 */
	private $sourceDirectory;

	/**
	 * Mask to find DIService annotation
	 * @var string
	 */
	private $mask;

	/**
	 * Default neon output file
	 * @var string
	 */
	private $outputFile;

	/**
	 * @var array<string>
	 * For splitting services to multiple neon files by namespace
	 * ```php
	 * [
	 *    'RootNamespace\Namespace1' => config1.neon
	 *    'RootNamespace\Namespace2' => config2.neon
	 * ]
	 * ```
	 * other services will be generated in $outputFile
	 */
	private $fileMapping;

	/**
	 * Mask for generated inject traits
	 * @var string
	 */
	private $injectMask;

	/**
	 * Mask for generated factories
	 * @var string
	 */
	private $factoryMask;

	/**
	 * Mask for generated components
	 * @var string
	 */
	private $componentMask;

	public function __construct(string $sourceDirectory, string $outputFile)
	{
		$this->sourceDirectory = $sourceDirectory;
		$this->outputFile = $outputFile;
		$this->mask = '*.php';
		$this->fileMapping = [];
		$this->injectMask = 'Inject%s';
		$this->factoryMask = '%sFactory';
		$this->componentMask = '%sComponent';
	}

	public function getSourceDirectory(): string
	{
		return $this->sourceDirectory;
	}

	public function setSourceDirectory(string $sourceDirectory): self
	{
		$this->sourceDirectory = $sourceDirectory;
		return $this;
	}

	public function getMask(): string
	{
		return $this->mask;
	}

	public function setMask(string $mask): self
	{
		$this->mask = $mask;
		return $this;
	}

	public function getOutputFile(): string
	{
		return $this->outputFile;
	}

	public function setOutputFile(string $outputFile): self
	{
		$this->outputFile = $outputFile;
		return $this;
	}

	/**
	 * @return array<string>
	 */
	public function getFileMapping(): array
	{
		return $this->fileMapping;
	}

	/**
	 * @param array<string> $fileMapping
	 */
	public function setFileMapping(array $fileMapping): self
	{
		$this->fileMapping = $fileMapping;
		return $this;
	}

	public function getInjectMask(): string
	{
		return $this->injectMask;
	}

	public function setInjectMask(string $injectMask): self
	{
		$this->injectMask = $injectMask;
		return $this;
	}

	public function getFactoryMask(): string
	{
		return $this->factoryMask;
	}

	public function setFactoryMask(string $factoryMask): self
	{
		$this->factoryMask = $factoryMask;
		return $this;
	}

	public function getComponentMask(): string
	{
		return $this->componentMask;
	}

	public function setComponentMask(string $componentMask): self
	{
		$this->componentMask = $componentMask;
		return $this;
	}

}
