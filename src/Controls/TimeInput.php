<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\Controls;

use Nette;
use Nette\Utils\Html;
use Nette\Forms\ISubmitterControl;
use Nette\Utils\Validators;
use Nette\Utils\DateTime;
use JanMotycka\Forms\Rules\FormRules;
use JanMotycka\Forms\Utils\Validators\DateTimeValidators;

class TimeInput extends AbstractDateTimeInput {

	use Nette\SmartObject;

	/** @var string */
	public const SELECTOR = 'timepicker';

	/**
	 * TimeInput constructor.
	 * @param null|string $label
	 * @param string      $format
	 */
	public function __construct(?string $label, string $format = 'H:i') {
		$this->format = $format;
		$this->badFormatAlert = 'Value must be time';
		parent::__construct($label, $this->format);
	}

	/**
	 * @return Html
	 */
	public function getControl(): Html {
		$this->addRule(FormRules::TIME, $this->badFormatAlert);

		$control = parent::getControl();

		//if control is a text type, there is need to add the class for the picker
		if ($control->getAttribute('type') === 'text') {
			$this->addHtmlClass(self::SELECTOR);
		}

		$control->addAttributes($this->attributes);
		return $control;
	}

	/**
	 * @return null|string
	 */
	public function getValue(): ?string {
		$validate = true; //Whether this element should be validated?
		$name = $this->getName();
		$isSubmitted = $this->getForm()->isSubmitted() instanceof ISubmitterControl;
		$controls = $isSubmitted ? $this->getForm()->isSubmitted()->getValidationScope() : null;

		if ($controls !== null && count($controls) === 0) { //if control not contained in validation scope then disable validation
			$validate = false;
		} else if ($controls !== null && count($controls) > 0) { //if control contained in validation scope then enable validation
			$validate = false;
			foreach ($controls as $control) {
				if ($control->getName() === $name) {
					$validate = true;
					break;
				}
			}
		}

		if (Validators::isNone($this->value)) {
			return null;
		}

		try {
			//try to create datetime from string input value if has not bad format
			new DateTime((string)$this->value);

			return $this->value;
		}
		catch (\Exception $exception) {
			if ($validate) {
				$this->addError($this->badFormatAlert);
			}

			return null;
		}
	}

	/**
	 * @param $value
	 * @return TimeInput
	 */
	public function setDefaultValue($value): self {
		if (Validators::isNone($value)) {
			parent::setDefaultValue('');
		} else if (!DateTimeValidators::isDateTime($value)) {
			throw new Nette\InvalidArgumentException('Default value is not time');
		} else if ($value instanceof DateTime) {
			parent::setDefaultValue($value->format($this->format));
		} else {
			parent::setDefaultValue($value);
		}

		return $this;
	}

}