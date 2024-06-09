<?php

declare(strict_types=1);

namespace Differ\Formatter;

use Differ\Formatters\Stylish;
use Differ\Formatters\Plain;
use RuntimeException;

const FORMAT_STYLISH = 'stylish';
const FORMAT_PLAIN = 'plain';

function format(string $format, array $data): string
{
    return match ($format) {
        FORMAT_STYLISH => Stylish\format($data),
        FORMAT_PLAIN => Plain\format($data),
        default => throw new RuntimeException(sprintf('Unknown data format: "%s"!', $format)),
    };
}
