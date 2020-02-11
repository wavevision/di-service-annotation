<?php declare (strict_types = 1);

namespace Wavevision\DIServiceAnnotationTests\Services\Text;

trait TextComponent
{

	private ControlFactory $textControlFactory;

	public function injectTextControlFactory(ControlFactory $controlFactory): void
	{
		$this->textControlFactory = $controlFactory;
	}

	public function getTextComponent(): Control
	{
		return $this['text'];
	}

	protected function createComponentText(): Control
	{
		return $this->textControlFactory->create();
	}

	protected function attachComponentText(Control $component): void
	{
		$this->addComponent($component, 'text');
	}

}
