# BEdita I18n Microsoft plugin

[![Github Actions](https://github.com/bedita/i18n-microsoft/workflows/php/badge.svg)](https://github.com/bedita/i18n-microsoft/actions?query=workflow%3Aphp)
[![codecov](https://codecov.io/gh/bedita/i18n-microsoft/branch/main/graph/badge.svg)](https://codecov.io/gh/bedita/i18n-microsoft)
[![phpstan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg)](https://phpstan.org)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bedita/i18n-microsoft/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/bedita/i18n-microsoft/?branch=main)
[![image](https://img.shields.io/packagist/v/bedita/i18n-microsoft.svg?label=stable)](https://packagist.org/packages/bedita/i18n-microsoft)
[![image](https://img.shields.io/github/license/bedita/i18n-microsoft.svg)](https://github.com/bedita/i18n-microsoft/blob/main/LICENSE.LGPL)

## Installation

You can install this plugin into your application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require bedita/i18n-microsoft
```

Note: php version supported is >= 7.4 and < 8.3.

## Microsoft Translator TEXT API

This plugin uses [Microsoft Translator TEXT API](https://www.microsoft.com/en-us/translator/business/translator-api/) to translate texts.

Usage example:
```php
use BEdita\I18n\Microsoft\Core\Translator;

$translator = new Translator();
$translator->setup([
    'auth_key' => 'your-auth-key', // Microsoft Translator KEY 1
    'location' => 'your-location', // Microsoft Translator Location/Region i.e. westeurope
]);
$result = $translator->translate(['Hello world!'], 'en', 'it');
// $result is an array, i.e ['translation' => ['Ciao mondo!']]
```
