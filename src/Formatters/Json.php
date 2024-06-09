<?php

declare(strict_types=1);

namespace Differ\Formatters\Json;

use JsonException;

/**
 * @throws JsonException
 */
function format(array $data): string
{
    return json_encode($data, JSON_THROW_ON_ERROR) . PHP_EOL;
}
