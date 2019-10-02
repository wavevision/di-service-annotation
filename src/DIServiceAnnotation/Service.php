<?php declare(strict_types = 1);

namespace Wavevision\DIServiceAnnotation;

use Nette\SmartObject;
use SplFileInfo;

class Service
{

	use SmartObject;

	/**
	 * @var DIService
	 */
	private $annotation;

	/**
	 * @var TokenizeResult
	 */
	private $tokenizeResult;

	/**
	 * @var SplFileInfo
	 */
	private $file;

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
