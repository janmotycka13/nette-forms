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


/**
 * Class DateInput
 * @package JanMotycka\Forms\Controls
 */
final class DateInput extends TextInput {

	use Nette\SmartObject;

	/** @var string  */
	public const SELECTOR = 'datepicker';

	/** @var string */
	private $badFormatAlert = 'Value must be date';

	/** @var string */
	private $format;

	/** @var array */
	private $attributes = [
		'class'         => [self::SELECTOR, 'form-control'],
		'autocomplete'  => 'false'
	];

	/**
	 * DateInput constructor.
	 * @param string $label
	 * @param string $format
	 */
	public function __construct(string $label, string $format = 'd.m.Y') {
		parent::__construct($label);
		$this->format = $format;
	}

	/**
	 * @return Html
	 */
	public function getControl(): Html {
		$this->addRule(FormRules::DATE, $this->badFormatAlert);

		$control = parent::getControl();

		//if control is a date type, there is no need to add the class for the picker
		if ($control->getAttribute('type') === 'date') {
			$this->removeHtmlClass(self::SELECTOR);
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
	 * @return bool
	 */
	public function isFilled(): bool {
		return !Validators::isNone($this->value);
	}

	/**
	 * @param mixed $value
	 * @throws Nette\InvalidArgumentException
	 * @return DateInput
	 */
	public function setDefaultValue($value): self {
		if (Validators::isNone($value)) {
			parent::setDefaultValue('');
		} else if (!DateTimeValidators::isDate($value)) {
			throw new Nette\InvalidArgumentException('Default value is not date');
		} else if ($value instanceof DateTime) {
			parent::setDefaultValue($value->format($this->format));
		} else {
			parent::setDefaultValue($value);
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @return DateInput
	 */
	public function addHtmlClass(string $name): self {
		$this->attributes['class'][] = $name;
		return $this;
	}

	/**
	 * @param string $name
	 * @return DateInput
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
	 * @return DateInput
	 */
	public function setBadFormatAlert(string $text): self {
		$translator = $this->getTranslator();
		$this->badFormatAlert = $translator === null ? $text : $translator->translate($text);

		return $this;
	}

}