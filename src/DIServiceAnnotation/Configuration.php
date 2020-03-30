<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\Generators\Component;
use Wavevision\DIServiceAnnotation\Generators\DefaultComponent;
use Wavevision\DIServiceAnnotation\Generators\DefaultFactory;
use Wavevision\DIServiceAnnotation\Generators\DefaultInject;
use Wavevision\DIServiceAnnotation\Generators\Factory;
use Wavevision\DIServiceAnnotation\Generators\Inject;
use Wavevision\Utils\Path;

final class Configuration
{

	use SmartObject;

	/**
	 * Where to find DIService annotation
	 */
	private string $sourceDirectory;

	/**
	 * Mask to find DIService annotation
	 */
	private string $mask;

	/**
	 * Default neon output file
	 */
	private string $outputFile;

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
	private array $fileMapping;

	private Inject $injectGenerator;

	private Factory $factoryGenerator;

	private Component $componentFactory;

	private ?string $autoloadFile;

	private Output $output;

	private ?string $tempDir;

	public function __construct(string $sourceDirectory, string $outputFile, bool $regenerate = false)
	{
		$this->sourceDirectory = $sourceDirectory;
		$this->outputFile = $outputFile;
		$this->mask = '*.php';
		$this->fileMapping = [];
		$templates = Path::create(__DIR__, 'Generators', 'templates');
		$this->autoloadFile = null;
		$this->output = new ConsoleOutput();
		$this->tempDir = null;
		$this->injectGenerator = new DefaultInject($this, 'Inject%s', $templates->string('inject.txt'), $regenerate);
		$this->factoryGenerator = new DefaultFactory(
			$this,
			'%sFactory',
			$templates->string('factory.txt'),
			$regenerate
		);
		$this->componentFactory = new DefaultComponent(
			$this,
			'%sComponent',
			$templates->string('component.txt'),
			$regenerate
		);
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

	public function getInjectGenerator(): Inject
	{
		return $this->injectGenerator;
	}

	/**
	 * @return static
	 */
	public function setInjectGenerator(Inject $injectGenerator)
	{
		$this->injectGenerator = $injectGenerator;
		return $this;
	}

	public function getFactoryGenerator(): Factory
	{
		return $this->factoryGenerator;
	}

	/**
	 * @return static
	 */
	public function setFactoryGenerator(Factory $factoryGenerator)
	{
		$this->factoryGenerator = $factoryGenerator;
		return $this;
	}

	public function getComponentFactory(): Component
	{
		return $this->componentFactory;
	}

	/**
	 * @return static
	 */
	public function setComponentFactory(Component $componentFactory)
	{
		$this->componentFactory = $componentFactory;
		return $this;
	}

	public function getAutoloadFile(): ?string
	{
		return $this->autoloadFile;
	}

	/**
	 * @return static
	 */
	public function setAutoloadFile(?string $autoloadFile)
	{
		$this->autoloadFile = $autoloadFile;
		return $this;
	}

	public function getOutput(): Output
	{
		return $this->output;
	}

	/**
	 * @return static
	 */
	public function setOutput(Output $output)
	{
		$this->output = $output;
		return $this;
	}

	public function getTempDir(): ?string
	{
		return $this->tempDir;
	}

	/**
	 * @return static
	 */
	public function setTempDir(?string $tempDir)
	{
		$this->tempDir = $tempDir;
		return $this;
	}

}
