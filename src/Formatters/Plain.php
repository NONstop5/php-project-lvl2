<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

use const Differ\Differ\ADDED;
use const Differ\Differ\CHANGED;
use const Differ\Differ\DELETED;
use const Differ\Differ\NESTED;
use const Differ\Differ\UNCHANGED;

const COMPARE_TEXT_MAP = [
    ADDED => 'added',
    DELETED => 'removed',
    CHANGED => 'updated',
    UNCHANGED => '',
    NESTED => '[complex value]',
];

function format(array $data): string
{
    return iter($data);
}

function iter(mixed $value, array $acc = []): string
{
    if (!is_array($value)) {
        return toString($value);
    }

    if (!array_key_exists(0, $value) && !array_key_exists('compare', $value)) {
        return toString($value);
    }

    $result = array_map(
        static function (mixed $val) use ($acc) {
            $key = $val['key'];
            $compare = $val['compare'];
            $compareText = getCompareText($compare);
            $accNew = [...$acc, ...[$key]];

            return match ($compare) {
                ADDED => sprintf(
                    "Property '%s' was %s with value: %s\n",
                    implode('.', $accNew),
                    $compareText,
                    iter($val['value'], $accNew),
                ),
                DELETED => sprintf(
                    "Property '%s' was %s\n",
                    implode('.', $accNew),
                    $compareText,
                ),
                CHANGED => sprintf(
                    "Property '%s' was %s. From %s to %s\n",
                    implode('.', $accNew),
                    $compareText,
                    iter($val['value1'], $accNew),
                    iter($val['value2'], $accNew),
                ),
                NESTED => iter($val['value'], $accNew),
                default => null,
            };
        },
        $value
    );

    return implode($result);
}

function getCompareText(string $compareText): string
{
    return COMPARE_TEXT_MAP[$compareText];
}

function toString(mixed $value): string
{
    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_array($value)) {
        return '[complex value]';
    }

    return trim(var_export($value, true), "'");
}
