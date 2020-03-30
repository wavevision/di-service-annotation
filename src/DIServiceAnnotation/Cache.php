<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Wavevision\Utils\Path;

class Cache
{

	use SmartObject;

	private Configuration $configuration;

	private bool $enabled;

	private array $cache;

	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
		$this->enabled = $configuration->getTempDir() !== null;
		if ($this->enabled) {
			$cacheFile = $this->getCacheFile();
			if (is_file($cacheFile)) {
				$this->cache = unserialize(FileSystem::read($cacheFile));
			} else {
				$this->cache = [];
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function get(string $key)
	{
		if ($this->enabled) {
			if (isset($this->cache[$key])) {
				return $this->cache[$key];
			}
		}
		return null;
	}

	/**
	 * @param array<mixed> $value
	 */
	public function set(string $key, array $value): void
	{
		if ($this->enabled) {
			$this->cache[$key] = $value;
		}
	}

	public function flush(): void
	{
		if ($this->enabled) {
			FileSystem::write($this->getCacheFile(), serialize($this->cache));
		}
	}

	private function getCacheFile(): string
	{
		$tempDir = $this->configuration->getTempDir();
		if ($tempDir === null) {
			throw new InvalidState('Temp directory not set.');
		}
		return Path::join($tempDir, '.di-service-annotation.cache');
	}

}
