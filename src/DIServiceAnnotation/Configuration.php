<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;

final class Configuration
{

	use SmartObject;

	/**
	 * @var string
	 */
	private $sourceDirectory;

	/**
	 * @var string
	 */
	private $mask;

	/**
	 * @var string
	 */
	private $outputFile;

	/**
	 * @var array<string>
	 */
	private $fileMapping;

	/**
	 * @var string
	 */
	private $injectMask;

	/**
	 * @var string
	 */
	private $factoryMask;

	public function __construct(string $sourceDirectory, string $outputFile)
	{
		$this->sourceDirectory = $sourceDirectory;
		$this->outputFile = $outputFile;
		$this->mask = '*.php';
		$this->fileMapping = [];
		$this->injectMask = 'Inject%s';
		$this->factoryMask = '%sFactory';
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
}
