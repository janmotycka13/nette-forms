<?php

declare(strict_types=1);

/**
 * Test: Check custom DateInput behavior.
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

namespace JanMotycka\Forms\Tests\DateTimeInputs;

use JanMotycka\Forms\Controls\DateInput;
use Nette\Forms\Form;
use Nette\DI\Container;
use Nette\InvalidArgumentException;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class DateInputTest extends TestCase implements IDateTimeInputTest {

	/** @var Container */
	private $container;

	/**
	 * DateInputTest constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container) {
		$this->container = $container;
	}

	public function setUp(): void {
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_GET = $_POST = $_FILES = [];
	}

	public function testSetDefaultValue(): void {
		//tests string value (not contains date format) as default value
		Assert::exception(function () {
			$form = new Form;
			$control = $form->addDateInput('date');
			$control->setDefaultValue('test date');
		}, InvalidArgumentException::class);

		//tests string value (contains date format) as default value
		Assert::noError(function () {
			$form = new Form;
			$control = $form->addDateInput('date');
			$control->setDefaultValue((new DateTime())->format('d.m.Y'));
		});

		//tests DateTimeInputs value as default value
		Assert::noError(function () {
			$form = new Form;
			$control = $form->addDateInput('date');
			$control->setDefaultValue(new DateTime());
		});
	}

	/**
	 * Tests whether the control does not add a datepicker css class if the html type is set to date and conversely
	 */
	public function testHtmlClass(): void {
		$form = new Form;
		$d1 = $form->addDateInput('d1')->setType('date');
		$d2 = $form->addDateInput('d2');

		Assert::notContains(DateInput::SELECTOR, $d1->getControl()->getAttribute('class'));
		Assert::contains(DateInput::SELECTOR, $d2->getControl()->getAttribute('class'));
	}

	/**
	 * Tests whether the value obtained corresponds to the expected value
	 */
	public function testReturnValue(): void {
		$form = new Form;
		$form->addDateInput('date1')->setValue((new DateTime())->format('d.m.Y'));
		$form->addDateInput('date2')->setValue('');
		$form->addDateInput('date3')->setValue(null);
		$form->addSubmit('send', 'Send');
		$form->validate();

		Assert::truthy($form->isSubmitted());
		Assert::true($form->isSuccess());

		$values = $form->getValues(true);
		Assert::type(DateTime::class, $values['date1']);
		Assert::null($values['date2']);
		Assert::null($values['date3']);
	}

	/**
	 * Tests allowed values
	 * @dataProvider getValidValues
	 * @param mixed $value
	 */
	public function testValidValues($value): void {
		$form = new Form;
		$form->addDateInput('date')->setValue($value);
		$form->addSubmit('send', 'Send');
		$form->validate();

		$values = $form->getValues(true);
		Assert::false($form->hasErrors());
	}

	/**
	 * @return array
	 */
	public function getValidValues(): array {
		return [
			['27.09.2018'], ['1.9.2018'], ['2018-09-27'],
		];
	}

	/**
	 * Tests disallowed values
	 * @dataProvider getInvalidValues
	 * @param mixed $value
	 */
	public function testInvalidValues($value): void {
		$form = new Form;
		$form->addDateInput('date')->setValue($value);
		$form->addSubmit('send', 'Send');
		$form->validate();

		Assert::truthy($form->isSubmitted());
		Assert::true($form->isSuccess());

		$values = $form->getValues(true);
		Assert::true($form->hasErrors());
	}

	/**
	 * @return array
	 */
	public function getInvalidValues(): array {
		return [
			['27..09.2018'], ['nette'], ['5'], [123],
		];
	}

	/**
	 * Tests whether control is filled if is it set.
	 */
	public function testFilled(): void {
		$form = new Form;
		$control1 = $form->addDateInput('date1')
			->setValue(new DateTime())
			->setRequired(true);
		$control2 = $form->addDateInput('date2')
		    ->setRequired(true);
		$form->addSubmit('send', 'Send');
		$form->validate();

		$values = $form->getValues(true);
		Assert::false($control1->hasErrors());
		Assert::true($control2->hasErrors());
	}

}

(new DateInputTest($container))->run();