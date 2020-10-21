<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Wavevision\Utils\Path;
use function is_file;
use function serialize;
use function unserialize;

class Cache
{

	use SmartObject;

	public const FILE = '.di-service-annotation.cache';

	private bool $enabled;

	/**
	 * @var array<mixed>
	 */
	private array $cache;

	private string $cacheFile;

	public function __construct(Configuration $configuration)
	{
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
