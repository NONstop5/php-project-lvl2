<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

use const Differ\Differ\ADDED;
use const Differ\Differ\DELETED;
use const Differ\Differ\UNCHANGED;
use const Differ\Differ\CHANGED;
use const Differ\Differ\NESTED;

const DEPTH_START = 1;
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
    return iter($data, DEPTH_START) . PHP_EOL;
}

function iter(mixed $value, int $depth): string
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
                $iterValue1 = iter($val['value1'], $depth + 1);
                $iterValue1 = $iterValue1 === ''
                    ? ''
                    : ' ' . $iterValue1
                ;

                $value1 = sprintf(
                    "%s%s %s:%s\n",
                    $indentValue,
                    getCompareSymbol(DELETED),
                    $key,
                    $iterValue1
                );

                $iterValue2 = iter($val['value2'], $depth + 1);
                $iterValue2 = $iterValue2 === ''
                    ? ''
                    : ' ' . $iterValue2
                ;

                $value2 = sprintf(
                    "%s%s %s:%s\n",
                    $indentValue,
                    getCompareSymbol(ADDED),
                    $key,
                    $iterValue2
                );

                return $value1 . $value2;
            }

            $compareSymbol = getCompareSymbol($compare);
            $value = iter($val['value'], $depth + 1);
            $value = $value === ''
                ? ''
                : ' ' . $value
            ;

            return sprintf(
                "%s%s %s:%s\n",
                $indentValue,
                $compareSymbol,
                $key,
                $value
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

    return "{\n" . implode($result) . "{$closeBracketIndent}}";
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
