<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Text;

use Nette\ComponentModel\IComponent;

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

	/**
	 * @return Control|IComponent
	 */
	public function getTextComponent(): IComponent
	{
		return $this['text'];
	}

}
