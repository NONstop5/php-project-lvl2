<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

use const Differ\Differ\ADDED;
use const Differ\Differ\DELETED;
use const Differ\Differ\UNCHANGED;

const INDENT_SYMBOL = '.';
const INDENT_COUNT = 2;
const COMPARE_TEXT_SYMBOL_MAP = [
    ADDED => '+',
    DELETED => '-',
    UNCHANGED => ' ',
];

function format(array $data): string
{
    return stringify($data);

    //$openBrace = "{\n";
    //$closeBrace = "}\n";

    //$result = array_reduce(
    //    $data,
    //    static function ($acc, $item) {
    //        $value = toString($item['value']);
    //        $compareSymbol = getCompareSymbol($item['compare']);
    //        return $acc . str_repeat(INDENT_SYMBOL, INDENT_COUNT) . "{$compareSymbol} {$item['key']}: {$value}\n";
    //    },
    //    ''
    //);

    //foreach ($data as $item) {
    //
    //}
    //
    //return "{$openBrace}{$result}{$closeBrace}";
}

function getCompareSymbol(string $compareText): string
{
    return COMPARE_TEXT_SYMBOL_MAP[$compareText];
}

function stringify(mixed $value): string
{
    return iter($value, INDENT_SYMBOL, INDENT_COUNT, 1);
}

function iter(mixed $value, string $replacer, int $indentCount, int $depth): string
{
    if (!is_array($value)) {
        return toString($value);
    }

    $indentSize = $depth * $indentCount;
    $indentValue = str_repeat($replacer, $indentSize);
    $closeBracketIndent = str_repeat($replacer, $indentSize - $indentCount);

    $result = array_map(
        static function ($key, $val) use ($replacer, $depth, $indentValue, $indentCount) {
            return "{$indentValue}{$key}: " . iter($val, $replacer, $indentCount, $depth + 1) . "\n";
        },
        array_keys($value),
        $value
    );

    return "{\n" . implode($result) . "{$closeBracketIndent}}";
}

function toString(mixed $value): string
{
    return trim(var_export($value, true), "'");
}
