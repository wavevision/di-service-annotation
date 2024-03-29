<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use ReflectionClass;
use SplFileInfo;
use Wavevision\Utils\Arrays;
use Wavevision\Utils\Tokenizer\Tokenizer;
use function implode;
use function ksort;
use function sprintf;
use const T_CLASS;
use const T_INTERFACE;

class ExtractServices
{

	private const TOKEN_TO_FACTORY = [
		T_CLASS => 'factory',
		T_INTERFACE => 'implement',
	];

	private AnnotationReader$annotationReader;

	private Tokenizer $tokenizer;

	private Configuration $configuration;

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
			/** @var class-string<object> $className */
			$className = $tokenizeResult->getFullyQualifiedName();
			$tokenId = $tokenizeResult->getToken();
			$file = $service->getFile();
			$generateFactory = $annotation->generateFactory || $annotation->generateComponent;
			$originalName = $tokenizeResult->getName();
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
				$this->generateComponent($className, $file, $originalName);
			}
			$lines = $this->generateConfig($annotation, $className, $tokenId, $lines);
		}
		FileSystem::write(
			$outputFile,
			"# Generated file, do not modify directly!\nservices:\n\t" . implode("\n\t", $lines) . "\n"
		);
	}

	/**
	 * @return Service[]
	 */
	private function findServices(): array
	{
		$fileValidator = new FileValidator($this->configuration);
		$services = [];
		/** @var  SplFileInfo $file */
		foreach (Finder::findFiles($this->configuration->getMask())->from(
			$this->configuration->getSourceDirectory()
		) as $file) {
			$pathname = $file->getPathname();
			$tokenizerResult = $this->tokenizer->getStructureNameFromFile($pathname, [T_CLASS, T_INTERFACE]);
			if ($tokenizerResult !== null) {
				/** @var class-string<object> $className */
				$className = $tokenizerResult->getFullyQualifiedName();
				if ($fileValidator->containsErrors($pathname, $className)) {
					continue;
				}
				$serviceAnnotation = $this->getAnnotation($className);
				if ($serviceAnnotation !== null) {
					$service = new Service($serviceAnnotation, $tokenizerResult, $file);
					$this->configuration->getOutput()->writeln(
						"Processing annotation in class: $className"
					);
					$services[$className] = $service;
				}
			}
		}
		$fileValidator->flushCache();
		return $services;
	}

	/**
	 * @param class-string<object> $className
	 */
	private function getAnnotation(string $className): ?DIService
	{
		$annotation = $this->annotationReader->getClassAnnotation(new ReflectionClass($className), DIService::class);
		if ($annotation instanceof DIService) {
			return $annotation;
		}
		return null;
	}

	/**
	 * @param string[] $lines
	 * @return string[]
	 */
	private function generateConfig(DIService $annotation, string $className, int $token, array $lines): array
	{
		$params = $annotation->params;
		$tags = $annotation->tags;
		$lines[] = sprintf(
			"%s %s: %s",
			isset($annotation->name) ? $annotation->name . ":\n\t " : '-',
			self::TOKEN_TO_FACTORY[$token],
			$className
		);
		if (!Arrays::isEmpty($params)) {
			$lines[] = $this->generateAttributes('arguments', $params);
		}
		if (!Arrays::isEmpty($tags)) {
			$lines[] = $this->generateAttributes('tags', $tags);
		}
		if ($annotation->enableInject) {
			$lines[] = "  inject: true";
		}
		return $lines;
	}

	/**
	 * @param string[] $attributes
	 */
	private function generateAttributes(string $name, array $attributes): string
	{
		return "  $name: [" . implode(', ', $attributes) . ']';
	}

	/**
	 * @param class-string<object> $className
	 * @return class-string<object>
	 */
	private function generateFactory(string $className, SplFileInfo $file): string
	{
		return $this->configuration->getFactoryGenerator()->generate(
			$this->getReflection($className),
			$file
		);
	}

	/**
	 * @param class-string<object> $className
	 */
	private function generateInject(string $className, SplFileInfo $file): void
	{
		$this->configuration->getInjectGenerator()->generate($this->getReflection($className), $file);
	}

	/**
	 * @param class-string<object> $className
	 */
	private function generateComponent(string $className, SplFileInfo $file, string $originalName): void
	{
		$this->configuration->getComponentFactory()->generate($this->getReflection($className), $file, $originalName);
	}

	/**
	 * @param class-string<object> $className
	 * @return ReflectionClass<object>
	 */
	private function getReflection(string $className): ReflectionClass
	{
		$reflection = new ReflectionClass($className);
		if ($reflection->getNamespaceName() === '') {
			throw new InvalidState(
				sprintf("Namespace is missing for '%s'. At least one level namespace is required.", $className)
			);
		}
		return $reflection;
	}

}
