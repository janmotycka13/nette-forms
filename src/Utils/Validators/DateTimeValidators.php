<?php

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

declare(strict_types=1);

namespace JanMotycka\Forms\Utils\Validators;

use Nette;
use Nette\Utils\Validators;
use DateTime as PHPDateTime;
use Nette\Utils\DateTime as NetteDateTime;

/**
 * Class DateValidator
 * @package JanMotycka\Forms\Validators
 */
final class DateTimeValidators extends Validators {

	use Nette\SmartObject;

	/**
	 * Check if value is date
	 * @param mixed $value
	 * @return bool
	 */
	public static function isDate($value): bool {
		if ($value instanceof PHPDateTime) {
			return true;
		} else {
			try {
				//try to create date from value of control
				new NetteDateTime($value);

				return true;
			}
			catch (\Exception $exception) {
				return false;
			}
		}
	}

}