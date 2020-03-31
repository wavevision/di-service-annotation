<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Wavevision\Utils\Path;

class Cache
{

	public const FILE = '.di-service-annotation.cache';

	use SmartObject;

	private Configuration $configuration;

	private bool $enabled;

	private array $cache;

	private string $cacheFile;

	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
		$tempDir = $configuration->getTempDir();
		$this->enabled = $tempDir !== null;
		if ($this->enabled) {
			$this->cacheFile = Path::join($tempDir, self::FILE);
			if (is_file($this->cacheFile)) {
				$this->cache = unserialize(FileSystem::read($this->cacheFile));
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
			FileSystem::write($this->cacheFile, serialize($this->cache));
		}
	}

}
