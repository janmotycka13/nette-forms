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

	public static function validateDate(IControl $control): bool {
		$value = $control->getValue();

		return DateTimeValidators::isNone($value) ? true : DateTimeValidators::isDate($value);
	}

}