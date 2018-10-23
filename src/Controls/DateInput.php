<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\Controls;

use JanMotycka\Forms\Utils\Validators\DateTimeValidators;
use Nette;
use Nette\Utils\DateTime;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;
use Nette\Utils\Validators;
use Nette\Forms\ISubmitterControl;
use JanMotycka\Forms\Rules\FormRules;
use Tracy\Debugger;


/**
 * Class DateInput
 * @package JanMotycka\Forms\Controls
 */
class DateInput extends AbstractDateTimeInput {

	use Nette\SmartObject;

	/** @var string  */
	public const SELECTOR = 'datepicker';

	/**
	 * DateInput constructor.
	 * @param null|string $label
	 * @param string      $format
	 */
	public function __construct(?string $label, string $format = 'd.m.Y') {
		$this->format = $format;
		$this->badFormatAlert = 'Value must be date';
		parent::__construct($label, $this->format);
	}

	/**
	 * @return Html
	 */
	public function getControl(): Html {
		$this->addRule(FormRules::DATE, $this->badFormatAlert);

		$control = parent::getControl();

		//if control is a text type, there is need to add the class for the picker
		if ($control->getAttribute('type') === 'text') {
			$this->addHtmlClass(self::SELECTOR);
		}

		$control->addAttributes($this->attributes);
		return $control;
	}

	/**
	 * @return DateTime|null
	 */
	public function getValue(): ?DateTime {
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
			return new DateTime( (string) $this->value );
		}
		catch (\Exception $exception) {
			if ($validate) {
				$this->addError($this->badFormatAlert);
			}

			return null;
		}
	}

	/**
	 * @param mixed $value
	 * @throws Nette\InvalidArgumentException
	 * @return DateInput
	 */
	public function setDefaultValue($value): self {
		if (Validators::isNone($value)) {
			parent::setDefaultValue('');
		} else if (!DateTimeValidators::isDateTime($value)) {
			throw new Nette\InvalidArgumentException('Default value is not date');
		} else if ($value instanceof DateTime) {
			parent::setDefaultValue($value->format($this->format));
		} else {
			parent::setDefaultValue($value);
		}

		return $this;
	}


}