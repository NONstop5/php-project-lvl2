<?php

declare(strict_types=1);

namespace Differ\Formatter;

use Differ\Formatters\Stylish;
use RuntimeException;

function format(string $format, array $data): string
{
    switch ($format) {
        case 'stylish':
            return Stylish\format($data);
        default:
            throw new RuntimeException(sprintf('Unknown data format: "%s"!', $format));
    }
}
