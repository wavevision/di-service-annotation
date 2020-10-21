<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use Wavevision\Utils\ExternalProgram\Executor;
use Wavevision\Utils\Path;
use function md5_file;

class FileValidator
{

	use SmartObject;

	private Configuration $configuration;

	private Cache $cache;

	public function __construct(Configuration $configuration)
	{
		$this->cache = new Cache($configuration);
		$this->configuration = $configuration;
	}

	public function containsErrors(string $pathname, string $className): bool
	{
		if ($autoload = $this->configuration->getAutoloadFile()) {
			$hash = md5_file($pathname);
			$cacheItem = $this->cache->get($pathname);
			if ($cacheItem !== null && $cacheItem['hash'] === $hash) {
				if ($cacheItem['error']) {
					$this->configuration->getOutput()->writeln(
						"Loading from cache: Fatal error in class $className"
					);
					return true;
				}
			} else {
				$classValidator = Path::join(__DIR__, '..', '..', 'bin', 'validate-class');
				$result = Executor::executeUnchecked("$classValidator '$autoload' '$className'");
				if ($result->getReturnValue() !== 0) {
					$this->configuration->getOutput()->writeln(
						"Fatal error in class $className\n" . $result->getOutputAsString()
					);
					$this->cache->set(
						$pathname,
						[
							'hash' => $hash,
							'error' => true,
						]
					);
					return true;
				}
			}
			$this->cache->set(
				$pathname,
				[
					'hash' => $hash,
					'error' => false,
				]
			);
		}
		return false;
	}

	public function flushCache(): void
	{
		$this->cache->flush();
	}

}
