<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use ReflectionClass;
use SplFileInfo;

class ExtractServices
{

	private const TOKEN_TO_FACTORY = [
		T_CLASS => 'factory',
		T_INTERFACE => 'implement',
	];

	/**
	 * @var AnnotationReader
	 */
	private $annotationReader;

	/**
	 * @var Tokenizer
	 */
	private $tokenizer;

	/**
	 * @var Configuration
	 */
	private $configuration;

	public function __construct(Configuration $configuration)
	{
		$this->annotationReader = new AnnotationReader();
		$this->tokenizer = new Tokenizer();
		$this->configuration = $configuration;
	}

	public function run(): void
	{
		$services = $this->findServices();
		$mappedByFile = [];
		foreach ($services as $className => $service) {
			$default = true;
			foreach ($this->configuration->getFileMapping() as $namespace => $file) {
				if (Strings::startsWith($className, $namespace)) {
					$default = false;
					$mappedByFile[$file][$className] = $service;
				}
			}
			if ($default) {
				$mappedByFile[$this->configuration->getOutputFile()][$className] = $service;
			}
		}
		foreach ($mappedByFile as $file => $services) {
			$this->saveFile($services, $file);
		}
	}

	/**
	 * @param Service[] $services
	 */
	private function saveFile(array $services, string $outputFile): void
	{
		ksort($services);
		$lines = [];
		foreach ($services as $service) {
			$annotation = $service->getAnnotation();
			$tokenizeResult = $service->getTokenizeResult();
			$className = $tokenizeResult->getFullyQualifiedName();
			$params = $annotation->params;
			$tokenId = $tokenizeResult->getToken();
			$file = $service->getFile();
			$generateFactory = $annotation->generateFactory || $annotation->generateComponent;
			$orginalName = $tokenizeResult->getName();
			if ($generateFactory) {
				if ($tokenId === T_INTERFACE) {
					throw new InvalidState('Unable to generate factory for interface.');
				}
				$className = $this->generateFactory($className, $file);
				$tokenId = T_INTERFACE;
			}
			if ($annotation->generateInject) {
				$this->generateInject($className, $file);
			}
			if ($annotation->generateComponent) {
				$this->generateComponent($className, $file, $orginalName);
			}
			$lines[] = sprintf("- %s: %s", self::TOKEN_TO_FACTORY[$tokenId], $className);
			if (count($params) > 0) {
				$lines[] = "  arguments: [" . implode(', ', $params) . ']';
			}
			$lines[] = "  inject: on";
		}
		FileSystem::write(
			$outputFile,
			"# generated file do not modify directly\nservices:\n\t" . implode("\n\t", $lines) . "\n"
		);
	}

	/**
	 * @return Service[]
	 */
	private function findServices(): array
	{
		$services = [];
		/** @var  SplFileInfo $file */
		foreach (Finder::findFiles($this->configuration->getMask())->from(
			$this->configuration->getSourceDirectory()
		) as $file) {
			$tokenizerResult = $this->tokenizer->getStructureNameFromFile($file->getPathname(), [T_CLASS, T_INTERFACE]);
			if ($tokenizerResult !== null) {
				$className = $tokenizerResult->getFullyQualifiedName();
				$serviceAnnotation = $this->getAnnotation($className);
				if ($serviceAnnotation !== null) {
					$services[$className] = new Service($serviceAnnotation, $tokenizerResult, $file);
				}
			}
		}
		return $services;
	}

	private function getAnnotation(string $className): ?DIService
	{
		$annotation = $this->annotationReader->getClassAnnotation(new ReflectionClass($className), DIService::class);
		if ($annotation instanceof DIService) {
			return $annotation;
		}
		return null;
	}

	private function generateFactory(string $className, SplFileInfo $file): string
	{
		$reflectionClass = $this->getReflection($className);
		$namespace = $reflectionClass->getNamespaceName();
		$name = $reflectionClass->getShortName();
		$factoryName = sprintf($this->configuration->getFactoryMask(), $name);
		$filename = dirname($file->getPathname()) . "/$factoryName.php";
		if (!is_file($filename)) {
			$this->renderTemplate(
				'factory',
				$filename,
				[
					$namespace,
					$factoryName,
					$name,
				]
			);
			require_once $filename;
		}
		$fullFactoryName = $namespace . '\\' . $factoryName;
		return $fullFactoryName;
	}

	private function generateInject(string $className, SplFileInfo $file): void
	{
		$reflectionClass = $this->getReflection($className);
		$namespace = $reflectionClass->getNamespaceName();
		$name = $reflectionClass->getShortName();
		$injectName = sprintf($this->configuration->getInjectMask(), $name);
		$this->renderTemplate(
			'inject',
			dirname($file->getPathname()) . "/$injectName.php",
			[
				$namespace,
				$injectName,
				$name,
				lcfirst($injectName),
				lcfirst($name),
			]
		);
	}

	private function generateComponent(string $className, SplFileInfo $file, string $originalName): void
	{
		$reflectionClass = $this->getReflection($className);
		$namespace = $reflectionClass->getNamespaceName();
		$namespaceParts = explode('\\', $namespace);
		$componentName = end($namespaceParts);
		$maskedComponentName = sprintf($this->configuration->getComponentMask(), $componentName);
		$type = $reflectionClass->getShortName();
		$this->renderTemplate(
			'component',
			dirname($file->getPathname()) . "/$maskedComponentName.php",
			[
				$namespace,
				$maskedComponentName,
				$type,
				lcfirst($type),
				lcfirst($componentName . $type),
				$componentName . $type,
				$componentName,
				$originalName,
			]
		);
	}

	/**
	 * @param array<mixed> $params
	 */
	private function renderTemplate(string $template, string $output, array $params): void
	{
		file_put_contents($output, sprintf(FileSystem::read(__DIR__ . '/templates/' . $template . '.txt'), ...$params));
	}

	private function getReflection(string $className): ReflectionClass
	{
		return new ReflectionClass($className);
	}
}
