<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\Controls;

use JanMotycka\Forms\Rules\FormRules;
use Nette;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;
use Nette\Utils\Validators;

abstract class AbstractDateTimeInput extends TextInput {

	use Nette\SmartObject;

	/** @var string */
	protected $format;

	/** @var string */
	protected $badFormatAlert = 'Value is not in correct format';

	/** @var array */
	protected $attributes = [
		'class'         => ['form-control'],
		'autocomplete'  => 'false'
	];

	/**
	 * DateTimeInput constructor.
	 * @param null|string $label
	 * @param string      $format
	 */
	public function __construct(?string $label, string $format) {
		parent::__construct($label);
		$this->format = $format;
	}

	/**
	 * @return Html
	 */
	public function getControl(): Html {
		$control = parent::getControl();
		$control->addAttributes($this->attributes);
		return $control;
	}

	/**
	 * @return bool
	 */
	public function isFilled(): bool {
		return !Validators::isNone($this->value);
	}

	/**
	 * @param string $name
	 * @return AbstractDateTimeInput
	 */
	public function addHtmlClass(string $name): self {
		$this->attributes['class'][] = $name;
		return $this;
	}

	/**
	 * @param string $name
	 * @return AbstractDateTimeInput
	 */
	public function removeHtmlClass(string $name): self {
		$index = array_search($name, $this->attributes['class'], true);

		if ($index !== false) {
			unset($this->attributes['class'][$index]);
		}

		return $this;
	}

	/**
	 * @param string $text
	 * @return AbstractDateTimeInput
	 */
	public function setBadFormatAlert(string $text): self {
		$translator = $this->getTranslator();
		$this->badFormatAlert = $translator === null ? $text : $translator->translate($text);

		return $this;
	}


}