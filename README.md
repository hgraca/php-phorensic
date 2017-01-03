# Hgraca\Phorensic
[![Author](http://img.shields.io/badge/author-@hgraca-blue.svg?style=flat-square)](https://www.herbertograca.com)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
[![Latest Version](https://img.shields.io/github/release/hgraca/php-phorensic.svg?style=flat-square)](https://github.com/hgraca/php-phorensic/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/hgraca/phorensic.svg?style=flat-square)](https://packagist.org/packages/hgraca/phorensic)

[![Build Status](https://img.shields.io/scrutinizer/build/g/hgraca/php-phorensic.svg?style=flat-square)](https://scrutinizer-ci.com/g/hgraca/php-phorensic/build)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/hgraca/php-phorensic.svg?style=flat-square)](https://scrutinizer-ci.com/g/hgraca/php-phorensic/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/hgraca/php-phorensic.svg?style=flat-square)](https://scrutinizer-ci.com/g/hgraca/php-phorensic)

A static analysis tool to gather a few metrics, like what code should have priority in refactoring.

The metrics aimed for are:
- Hotspots detection (complexity * change_rate)
    - Prioritize code for refactoring 
- Code units temporal coupling
    - If there is no reason for them to change together (unlike tests and the code they test), they should probably be refactored
- Code units ownership analysis
    - To detect team knowledge deficiencies

## Usage

This tool works in 2 steps:

1. Mine the project for data, which is put in a sqlite DB
    - `phorensic:extract [<repositoryPath>] [<since>] [<dbPath>]`
2. Query a sqlite DB for information 
    - `phorensic:analyse <dbPath> [<limit>]`

So, for example:

```bash
bin/run phorensic:extract /opt/my_project "last month" "./analyse_2016-12-20_23:51:36.sqlite"
bin/run phorensic:analyse "./analyse_2016-12-20_23:51:36.sqlite" 20
```

## Installation

To install the library, run the command below and you will get the latest version:

```
composer require hgraca/phorensic
```

## Tests

To run the tests run:
```bash
make test
```
Or just one of the following:
```bash
make test-acceptance
make test-functional
make test-integration
make test-unit
make test-humbug
```
To run the tests in debug mode run:
```bash
make test-debug
```

## Coverage

To generate the test coverage run:
```bash
make coverage
```

## Code standards

To fix the code standards run:
```bash
make cs-fix
```

## Todo

- Create Builder class so that we can do dep inj with the commands and make them testable
- Test the commands
+ Create command to find classes with temporal-coupling
+ Create command to find specific class ownership
+ Create command to find specific package ownership
+ Create command to find ownership analysis (ownership fractals)
