<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\Controls;

use Nette;
use Nette\Forms\Controls\TextArea;
use Nette\Utils\Html;
use Nette\Utils\Validators;

class WysiwygTextArea extends TextArea {

	use Nette\SmartObject;

	/** @var string */
	public const SELECTOR = 'wysiwyg';

	/** @var array */
	private $attributes = [
		'class' => [self::SELECTOR, 'form-control']
	];

	public function getControl(): Html {
		$control = parent::getControl();
		$control->addAttributes($this->attributes);
		return $control;
	}

	public function getValue(): ?string {
		return Validators::isNone($this->value) ? null : $this->value;
	}


}