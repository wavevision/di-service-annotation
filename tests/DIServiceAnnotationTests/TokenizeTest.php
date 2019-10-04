<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests;

use Nette\Utils\FileSystem;
use org\bovigo\vfs\vfsStream as vf;
use PHPUnit\Framework\TestCase;
use Wavevision\DIServiceAnnotation\Tokenizer;

class TokenizeTest extends TestCase
{

	public function testEmpty(): void
	{
		vf::setup('r');
		$file = vf::url('r/file.php');
		FileSystem::write($file, '<?php');
		$this->assertSame(null, (new Tokenizer())->getStructureNameFromFile($file, [T_CLASS]));
	}
}
