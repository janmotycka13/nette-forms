<?php

declare(strict_types=1);

/**
 * Test: Check wysiwyg text area.
 * @author Ing. Jan Motyčka <janmotycka@post.cz>
 */

namespace JanMotycka\Forms\Tests\TextAreas;

use JanMotycka\Forms\Controls\WysiwygTextArea;
use Nette;
use Tester;
use Tester\Assert;
use Nette\Utils\Html;
use Nette\Forms\Form;


$container = require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class WysiwygTextAreaTest extends Tester\TestCase {

	private $container;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}

	public function setUp(): void {
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_GET = $_POST = $_FILES = [];
	}

	/**
	 * Test checks whether the control has a html class wysivyg that is necessary for proper rendering
	 */
	public function testHasHtmlClass(): void {
		$form = new Form;
		$control = $form->addWysiwygTextArea('wysiwyg');

		Assert::contains(WysiwygTextArea::SELECTOR, $control->getControl()->getAttribute('class'), sprintf('The control does not contain html class %s for correct rendering.', WysiwygTextArea::SELECTOR));
	}

	/**
	 * @dataProvider getValues
	 * @param string      $testedValue
	 * @param null|string $expectedValue
	 */
	public function testReturnValue(string $testedValue, ?string $expectedValue): void {
		$form = new Form;
		$form->addWysiwygTextArea('wysiwyg')
			->setValue($testedValue);
		$form->addSubmit('send', 'Send');

		Assert::truthy($form->isSubmitted());

		$values = $form->getValues(true);
		Assert::same($expectedValue, $values['wysiwyg']);
	}

	/**
	 * @return array
	 */
	public function getValues(): array {
		$htmlValue = Html::el('p')->addText('nette')->render();
		return [
			[$htmlValue, $htmlValue],
			['', null]
		];
	}

}

(new WysiwygTextAreaTest($container))->run();