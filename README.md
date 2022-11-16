# PHP Python Bridge

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nikaia/php-python-bridge.svg?style=flat-square)](https://packagist.org/packages/nikaia/php-python-bridge)
[![Tests](https://github.com/nikaia/php-python-bridge/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/nikaia/php-python-bridge/actions/workflows/tests.yml)

Call your Python scripts from Php

## Installation

You can install the package via composer:

```bash
composer require nikaia/php-python-bridge
```

## Usage

The bridge work by executing a python script that accept piped json arbitary data, and returns json response.

> check [tests/_fixtures/ok.script.py](tests/_fixtures/ok.script.py) for a working example.


```php
use Nikaia\PythonBridge\Bridge;

try {
    $response = Bridge::create()
        ->setPython('/usr/local/bin/python')        // the path to the node (You can omit if in system path)
        ->setScript('/path/to/your/script.py')  // the path to your script 
        ->pipe(['foo' => 'bar'])                // the data to pipe to the script
        ->run();
}
catch (BridgeException $e) {
    echo $e->getMessage();
}

var_dump($response->json());   // ['foo' => 'bar']
var_dump($response->output()); // the raw output of the script {"foo":"bar"}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Releases

This package use [semver](https://semver.org/) releases.
Releases are managed using [standard-version](https://github.com/conventional-changelog/standard-version) node package,
and requires adhering to [conventionalcommit](https://conventionalcommits.org) commit styles.

1. Implement a feature or a fix etc.
2. Use commit message like `fix: Fix an issue` or `feat: Implement a feature ...`
3. Or rewrite the commit message while squashing/closing the PR!!
4. Update your local project, checkout `main` branch
5. Run : `composer release` to generate changelog, and to tag to new release.
6. Check everything is okay.
7. Push the tag using `git push --follow-tags origin main`


> This repository is using [Semantic Pull Request bot](https://github.com/zeke/semantic-pull-requests) to enforce conventional commit message and PR titles


## Credits

- [Nassif Bourguig](https://github.com/nbourguig)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
