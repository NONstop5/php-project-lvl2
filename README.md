### Hexlet tests and linter status:
[![Actions Status](https://github.com/NONstop5/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/NONstop5/php-project-lvl2/actions)
[![Main](https://github.com/NONstop5/php-project-lvl2/actions/workflows/main.yml/badge.svg)](https://github.com/NONstop5/php-project-lvl2/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/959acf8bd094de9ffdb8/maintainability)](https://codeclimate.com/github/NONstop5/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/959acf8bd094de9ffdb8/test_coverage)](https://codeclimate.com/github/NONstop5/php-project-lvl2/test_coverage)

## Library "Differ"

### Requirements
- PHP >= 8.2
- Composer >= 2
- make >= 4

### Installation
> `git clone git@github.com:NONstop5/php-project-lvl2.git`
> 
> `make install`

### Help
>`gendiff -h`

### Description

```php
<?php

declare(strict_types=1);

use function Differ\Differ\genDiff;

genDiff(string $firstFilePath, string $secondFilePath, string $format = 'stylish'): string
```
> - `$firstFilePath` - path to json or yaml file
> - `$secondFilePath` - path to json or yaml file
> - `$format` - output format type, can be `stylish`, `plain`, `json` (default: `stylish`)

> <a href="https://asciinema.org/a/XR3E5U9ycfXG757OVNxF7p7Bx">Json plain diff Demo</a>
>
> <a href="https://asciinema.org/a/ql778qebUxWyT480tsQ8a8RYU">Yaml plain diff Demo</a>

> <a href="https://asciinema.org/a/SCUkOAZFdnw0wOMj00QjP6hC5">Json and Yaml plain/nested diff in **stylish format** Demo</a>

> <a href="https://asciinema.org/a/2KBsyQsfepJjin1qQ9ikQGU9Y">Json and Yaml nested in **plain format** diff Demo</a>

> <a href="https://asciinema.org/a/qB2G9FE0DD18gE7mELUcFimJY">Json and Yaml nested in **json format** diff Demo</a>
