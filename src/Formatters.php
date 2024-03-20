<?php

declare(strict_types=1);

namespace Differ\Formatters;

function format(string $format, array $data): string
{
    $openBrace = "{\n";
    $closeBrace = "}\n";

    $result = array_reduce(
        $data,
        static function ($acc, $item) {
            $value = var_export($item['value'], true);

            return $acc . "  {$item['compare']} {$item['key']}: {$value}\n";
        },
        ''
    );

    return "{$openBrace}{$result}{$closeBrace}";
}
