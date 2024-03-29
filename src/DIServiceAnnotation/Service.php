<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use SplFileInfo;
use Wavevision\Utils\Tokenizer\TokenizeResult;

class Service
{

	use SmartObject;

	private DIService $annotation;

	private TokenizeResult $tokenizeResult;

	private SplFileInfo $file;

	public function __construct(DIService $annotation, TokenizeResult $tokenizeResult, SplFileInfo $file)
	{
		$this->annotation = $annotation;
		$this->tokenizeResult = $tokenizeResult;
		$this->file = $file;
	}

	public function getAnnotation(): DIService
	{
		return $this->annotation;
	}

	public function getTokenizeResult(): TokenizeResult
	{
		return $this->tokenizeResult;
	}

	public function getFile(): SplFileInfo
	{
		return $this->file;
	}

}
