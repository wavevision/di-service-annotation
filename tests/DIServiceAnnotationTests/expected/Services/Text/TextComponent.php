<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Text;

trait TextComponent
{

	/**
	 * @var ControlFactory
	 */
	private $textControlFactory;

	public function injectTextControlFactory(ControlFactory $controlFactory): void
	{
		$this->textControlFactory = $controlFactory;
	}

	public function createComponentText(): Control
	{
		return $this->textControlFactory->create();
	}

	public function getTextComponent(): Control
	{
		return $this['text'];
	}

}
