<?php

declare(strict_types=1);

/**
 * Test: Check custom TimeInput behavior.
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */


namespace JanMotycka\Forms\Tests\DateTimeInputs;

use JanMotycka\Forms\Controls\TimeInput;
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
class TimeInputTest extends TestCase {

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
		//tests string value (not contains time format) as default value
		Assert::exception(function () {
			$form = new Form;
			$control = $form->addTimeInput('time');
			$control->setDefaultValue('test time');
		}, InvalidArgumentException::class);

		//tests string value (contains date format) as default value
		Assert::noError(function () {
			$form = new Form;
			$control = $form->addTimeInput('time');
			$control->setDefaultValue((new DateTime)->format('H:i'));
		});

		//tests DateTimeInputs value as default value
		Assert::noError(function () {
			$form = new Form;
			$control = $form->addTimeInput('time');
			$control->setDefaultValue(new DateTime);
		});
	}

	/**
	 * Tests whether the control does not add a timepicker css class if the html type is set to date and
	 * conversely
	 */
	public function testHtmlClass(): void {
		$form = new Form;
		$t1 = $form->addTimeInput('t1')->setType('date');
		$t2 = $form->addTimeInput('t2');

		Assert::notContains(TimeInput::SELECTOR, $t1->getControl()->getAttribute('class'));
		Assert::contains(TimeInput::SELECTOR, $t2->getControl()->getAttribute('class'));
	}

	public function testReturnValue(): void {
		$form = new Form;
		$form->addTimeInput('time1')->setValue((new DateTime())->format('H:i'));
		$form->addTimeInput('time2')->setValue('');
		$form->addTimeInput('time3')->setValue(null);
		$form->addSubmit('send', 'Send');
		$form->validate();

		Assert::truthy($form->isSubmitted());
		Assert::true($form->isSuccess());

		$values = $form->getValues(true);
		Assert::type('string', $values['time1']);
		Assert::null($values['time2']);
		Assert::null($values['time3']);
	}

	/**
	 * Tests allowed values
	 * @dataProvider getValidValues
	 * @param mixed $value
	 */
	public function testValidValues($value): void {
		$form = new Form;
		$form->addTimeInput('time')->setValue($value);
		$form->addSubmit('send', 'Send');
		$form->validate();

		$values = $form->getValues(true);
		Assert::false($form->hasErrors());
	}

	/**
	 * @return array
	 */
	public function getValidValues(): array {
		return [['09:30'], ['9:30'], ['00:00'],];
	}

	/**
	 * Tests disallowed values
	 * @dataProvider getInvalidValues
	 * @param mixed $value
	 */
	public function testInvalidValues($value): void {
		$form = new Form;
		$form->addTimeInput('time')->setValue($value);
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
		return [['08::40'], ['nette'], ['5'], [123],];
	}

	public function testFilled(): void {
		$form = new Form;
		$control1 = $form->addTimeInput('time1')->setValue((new DateTime())->format('H:i'))->setRequired(true);
		$control2 = $form->addTimeInput('time2')->setRequired(true);
		$form->addSubmit('send', 'Send');
		$form->validate();

		$values = $form->getValues(true);
		Assert::false($control1->hasErrors());
		Assert::true($control2->hasErrors());
	}


}

(new TimeInputTest($container))->run();