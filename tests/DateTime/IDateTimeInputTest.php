<?php

declare(strict_types=1);

/**
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 * @skip
 */

namespace JanMotycka\Forms\Tests\DateTime;

/**
 * Interface IDateInput
 * @package JanMotycka\Forms\Tests\DateTime
 *
 */
interface IDateTimeInputTest {

	public function setUp(): void;

	public function testSetDefaultValue(): void;

	public function testHtmlClass(): void;

	public function testReturnValue(): void;

	public function testValidValues($value): void;

	public function getValidValues(): array;

	public function testInvalidValues($value): void;

	public function getInvalidValues(): array;

	public function testFilled(): void;

}