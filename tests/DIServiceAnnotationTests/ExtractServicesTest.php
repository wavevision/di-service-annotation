<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Wavevision\DIServiceAnnotation\Cache;
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\ExtractServices;
use Wavevision\DIServiceAnnotation\Generators\DefaultComponent;
use Wavevision\DIServiceAnnotation\Generators\DefaultFactory;
use Wavevision\DIServiceAnnotation\Generators\DefaultGenerator;
use Wavevision\DIServiceAnnotation\InvalidState;
use Wavevision\DIServiceAnnotation\VoidOutput;
use Wavevision\Utils\Path;
use function array_push;
use function explode;
use function is_file;
use function trim;

class ExtractServicesTest extends TestCase
{

	private const NESTED_NEON = 'nested.neon';

	private const DEFAULT_NEON = 'default.neon';

	public function testRun(): void
	{
		$this->setOutputCallback(fn() => null);
		$tempDir = Path::join(__DIR__, '..', '..', 'temp');
		$cacheFile = Path::join($tempDir, Cache::FILE);
		FileSystem::delete($cacheFile);
		// run without cache
		$this->extractServices($tempDir);
		// run with cache
		$this->extractServices($tempDir);
	}

	public function testInvalidState(): void
	{
		$this->expectException(InvalidState::class);
		$extractServices = new ExtractServices(
			(new Configuration($this->path('InvalidState'), $this->resultNeon(self::DEFAULT_NEON)))->setOutput(
				new VoidOutput()
			)
		);
		$extractServices->run();
	}

	public function testNoNamespace(): void
	{
		$this->setOutputCallback(fn() => null);
		vfsStream::setup('r');
		$servicesDirectory = vfsStream::url('r/d');
		FileSystem::createDir($servicesDirectory);
		$component = vfsStream::url('r/d/Component.php');
		FileSystem::write(
			$component,
			'<?php 
			use Wavevision\DIServiceAnnotation\DIService;
			/**
			 * @DIService(generateComponent=true)
			 */
			 class Control {
			 }'
		);
		include $component;
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage("Namespace is missing for 'Control'. At least one level namespace is required.");
		$extractServices = new ExtractServices(
			(new Configuration($servicesDirectory, ''))
		);
		$extractServices->run();
	}

	/**
	 * @return array<string>
	 */
	private function getFilesToCreate(): array
	{
		$filesToCreate = explode("\n", trim(FileSystem::read($this->path('Services/.gitignore'))));
		foreach ($filesToCreate as $key => $file) {
			$pathname = $this->path('Services', $file);
			$filesToCreate[$key] = $pathname;
			if (is_file($pathname)) {
				FileSystem::delete($pathname);
			}
		}
		return $filesToCreate;
	}

	private function assertSameConfig(string $config): void
	{
		$this->assertSameFileContent($this->neon('expected', $config), $this->resultNeon($config));
	}

	private function resultNeon(string $name): string
	{
		return $this->neon('testResult', $name);
	}

	private function assertSameFileContent(string $expected, string $actual): void
	{
		$this->assertSame(FileSystem::read($expected), FileSystem::read($actual));
	}

	private function neon(string $dir, string $name): string
	{
		return $this->path($dir, $name);
	}

	private function path(string ...$parts): string
	{
		$p = [__DIR__];
		array_push($p, ...$parts);
		return Path::join(...$p);
	}

	private function extractServices(string $tempDir): void
	{
		$filesToCreate = $this->getFilesToCreate();
		$filesToCreate[] = $this->path('Services', 'InjectExistingInject.php');
		$servicesDir = __DIR__ . '/Services';
		$templates = Path::create(__DIR__, '..', '..', 'src', 'DIServiceAnnotation', 'Generators', 'templates');
		$configuration = (new Configuration($servicesDir, $this->resultNeon(self::DEFAULT_NEON)));
		$configuration
			->setMask('*.php')
			->setSourceDirectory($servicesDir)
			->setOutputFile($this->resultNeon(self::DEFAULT_NEON))
			->setFactoryGenerator(
				new DefaultFactory($configuration, '%sFactory', $templates->string('factory.txt'))
			)
			->setComponentFactory(
				new DefaultComponent($configuration, '%sComponent', $templates->string('component.txt'))
			)
			->setFileMapping(
				[
					'Wavevision\DIServiceAnnotationTests\Services\Nested' => $this->resultNeon(self::NESTED_NEON),
				]
			)
			->setRegenerate(false)
			->enableFileValidation(__DIR__ . '/../bootstrap.php', $tempDir);
		$configuration->setInjectGenerator($configuration->getInjectGenerator());
		/** @var DefaultGenerator $injectGenerator */
		$injectGenerator = $configuration->getInjectGenerator();
		$injectGenerator->setMask($injectGenerator->getMask());
		$injectGenerator->setTemplate($injectGenerator->getTemplate());
		$extractServices = new ExtractServices($configuration);
		$extractServices->run();
		$this->assertSameConfig(self::DEFAULT_NEON);
		$this->assertSameConfig(self::NESTED_NEON);
		foreach ($filesToCreate as $file) {
			$this->assertFileExists($file);
			$this->assertSameFileContent(Strings::replace($file, '/Services/', '/expected/Services/'), $file);
		}
	}

}
