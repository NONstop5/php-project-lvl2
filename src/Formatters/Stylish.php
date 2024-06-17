<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

use const Differ\Differ\ADDED;
use const Differ\Differ\DELETED;
use const Differ\Differ\UNCHANGED;
use const Differ\Differ\CHANGED;
use const Differ\Differ\NESTED;

const INDENT_SYMBOL = ' ';
const INDENT_COUNT = 4;
const COMPARE_SYMBOL_LENGTH = 2;
const COMPARE_TEXT_SYMBOL_MAP = [
    ADDED => '+',
    DELETED => '-',
    UNCHANGED => ' ',
    CHANGED => ' ',
    NESTED => ' ',
];

function format(array $data): string
{
    return iter($data) . PHP_EOL;
}

function iter(mixed $value, int $depth = 1): string
{
    if (!is_array($value)) {
        return stringify($value, $depth);
    }

    if (!array_key_exists(0, $value) && !array_key_exists('compare', $value)) {
        return stringify($value, $depth);
    }

    $indentSize = $depth * INDENT_COUNT - COMPARE_SYMBOL_LENGTH;
    $indentValue = str_repeat(INDENT_SYMBOL, $indentSize);

    $closeBracketIndentSize =  $indentSize - INDENT_COUNT + COMPARE_SYMBOL_LENGTH;
    $closeBracketIndent = $closeBracketIndentSize > 0 ?
        str_repeat(INDENT_SYMBOL, $closeBracketIndentSize)
        : ''
    ;

    $result = array_map(
        static function ($val) use ($depth, $indentValue) {
            $key = $val['key'];
            $compare = $val['compare'];

            if ($compare === CHANGED) {
                $value1 = sprintf(
                    "%s%s %s: %s\n",
                    $indentValue,
                    getCompareSymbol(DELETED),
                    $key,
                    iter($val['value1'], $depth + 1)
                );

                $value2 = sprintf(
                    "%s%s %s: %s\n",
                    $indentValue,
                    getCompareSymbol(ADDED),
                    $key,
                    iter($val['value2'], $depth + 1)
                );

                return $value1 . $value2;
            }

            $compareSymbol = getCompareSymbol($compare);

            return sprintf(
                "%s%s %s: %s\n",
                $indentValue,
                $compareSymbol,
                $key,
                iter($val['value'], $depth + 1)
            );
        },
        $value
    );

    return "{\n" . implode($result) . "{$closeBracketIndent}}";
}

function stringify(mixed $value, int $depth): string
{
    return stringifyIter($value, $depth);
}

function stringifyIter(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return toString($value);
    }

    $indentSize = $depth * INDENT_COUNT;
    $indentValue = str_repeat(INDENT_SYMBOL, $indentSize);
    $closeBracketIndent = str_repeat(INDENT_SYMBOL, $indentSize - INDENT_COUNT);

    $result = array_map(
        static function ($key, $val) use ($depth, $indentValue) {
            return sprintf(
                "%s%s: %s\n",
                $indentValue,
                $key,
                iter($val, $depth + 1)
            );
        },
        array_keys($value),
        $value
    );

    return sprintf(
        "{\n%s%s}",
        implode($result),
        $closeBracketIndent
    );
}

function getCompareSymbol(string $compareText): string
{
    return COMPARE_TEXT_SYMBOL_MAP[$compareText];
}

function toString(mixed $value): string
{
    if (is_null($value)) {
        return 'null';
    }

    return trim(var_export($value, true), "'");
}
