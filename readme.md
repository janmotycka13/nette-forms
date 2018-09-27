# Nette Form Controls
Extension of Nette/Forms with other controls
## Installation
- It requires PHP 7.1 and upper
- The recommended way to install is via Composer:
```
composer require janmotycka/nette-forms
```
- Include js/forms.js (or copy content to @layout.latte)
- Must be installed: **[Bootstrap 4](https://www.npmjs.com/package/bootstrap)**, **[netteForms.js](https://www.npmjs.com/package/nette-forms)**, **[bootstrap-datepicker](https://www.npmjs.com/package/bootstrap-datepicker)** and **[Moment.js](https://www.npmjs.com/package/moment)**

## Usage
#### DateInput
Add DateInput with today's date
```php
$form->addDateInput('date', 'Datum')
	->setDafaultValue(new DateTime())
	->setBadFormatAlert('This field has no date format');
```