<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\Rules;

use Nette;
use Nette\Forms\IControl;
use JanMotycka\Forms\Utils\Validators\DateTimeValidators;

/**
 * Class FormRules
 * @package JanMotycka\Forms\Rules
 */
final class FormRules {

	use Nette\SmartObject;

	/** @var string */
	public const DATE = FormRules::class . '::validateDate';

	/** @var string */
	public const TIME = FormRules::class . '::validateTime';

	/**
	 * @param IControl $control
	 * @return bool
	 */
	public static function validateDate(IControl $control): bool {
		$value = $control->getValue();

		return DateTimeValidators::isNone($value) ? true : DateTimeValidators::isDateTime($value);
	}

	/**
	 * @param IControl $control
	 * @return bool
	 */
	public static function validateTime(IControl $control): bool {
		$value = $control->getValue();

		return DateTimeValidators::isNone($value) ? true : DateTimeValidators::isTime($value);
	}

}