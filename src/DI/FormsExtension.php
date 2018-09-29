<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\DI;

use JanMotycka\Forms\Controls\WysiwygTextArea;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\Forms\Container;
use Nette\PhpGenerator\ClassType;
use JanMotycka\Forms\Controls\DateInput;

/**
 * Class FormsExtension
 * @package JanMotycka\Forms\DI
 */
class FormsExtension extends CompilerExtension {

	use Nette\SmartObject;

	/**
	 * @param Nette\PhpGenerator\ClassType $class
	 */
	public function afterCompile(ClassType $class): void {
		$initialize = $class->getMethod('initialize');

		$initialize->addBody(sprintf(
			'%s::extensionMethod(\'addDateInput\',
			function (%s $form, $name, $title = \'Please insert date\') {
				$component = new %s($title);
				$form->addComponent($component, $name);
				return $component;
			}
			);', Container::class, Container::class, DateInput::class
		));

		$initialize->addBody(sprintf(
			'%s::extensionMethod(\'addWysiwygTextArea\',
			function (%s $form, $name, $title = \'Please insert text\') {
				$component = new %s($title);
				$form->addComponent($component, $name);
				return $component;
			}
			);', Container::class, Container::class, WysiwygTextArea::class
		));
	}


}