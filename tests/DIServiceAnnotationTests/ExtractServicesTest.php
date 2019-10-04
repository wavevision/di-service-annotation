<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PHPUnit\Framework\TestCase;
use Wavevision\DIServiceAnnotation\Configuration;
use Wavevision\DIServiceAnnotation\ExtractServices;
use Wavevision\DIServiceAnnotation\InvalidState;

class ExtractServicesTest extends TestCase
{

	private const NESTED_NEON = 'nested.neon';

	private const DEFAULT_NEON = 'default.neon';

	public function testRun(): void
	{
		$filesToCreate = $this->getFilesToCreate();
		$servicesDir = __DIR__ . '/Services';
		$extractServices = new ExtractServices(
			(new Configuration($servicesDir, $this->resultNeon(self::DEFAULT_NEON)))
				->setMask('*.php')
				->setSourceDirectory($servicesDir)
				->setOutputFile($this->resultNeon(self::DEFAULT_NEON))
				->setInjectMask('Inject%s')
				->setFactoryMask('%sFactory')
				->setFileMapping(
					[
						'Wavevision\DIServiceAnnotationTests\Services\Nested' => $this->resultNeon(self::NESTED_NEON),
					]
				)
		);
		$extractServices->run();
		$this->assertSameConfig(self::DEFAULT_NEON);
		$this->assertSameConfig(self::NESTED_NEON);
		foreach ($filesToCreate as $file) {
			$this->assertFileExists($file);
			$this->assertSameFileContent($file, Strings::replace($file, '/Services/', '/expected/Services/'));
		}
	}

	public function testInvalidState(): void
	{
		$this->expectException(InvalidState::class);
		$extractServices = new ExtractServices(
			(new Configuration($this->path('InvalidState'), $this->resultNeon(self::DEFAULT_NEON)))
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
		return join('/', $p);
	}
}
