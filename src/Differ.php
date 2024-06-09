<?php

declare(strict_types=1);

namespace Differ\Differ;

use JsonException;

use function Differ\DataGetter\getFileData;
use function Differ\Formatter\format;
use function Differ\Parser\parse;
use function Functional\sort;

const UNCHANGED = 'unchanged';
const CHANGED = 'changed';
const ADDED = 'added';
const DELETED = 'deleted';
const NESTED = 'nested';

/**
 * @param string $pathToFile1
 * @param string $pathToFile2
 * @param string $format
 *
 * @return string
 * @throws JsonException
 */
function genDiff(
    string $pathToFile1,
    string $pathToFile2,
    string $format = 'stylish'
): string {
    [
        'dataFormat' => $dataFormat1,
        'rawData' => $rawData1,
    ] = getFileData($pathToFile1);
    [
        'dataFormat' => $dataFormat2,
        'rawData' => $rawData2,
    ] = getFileData($pathToFile2);

    $fileData1 = parse($dataFormat1, $rawData1);
    $fileData2 = parse($dataFormat2, $rawData2);

    $dataDiff = buildDiffData($fileData1, $fileData2);

    return format($format, $dataDiff);
}

function buildDiffData(array $data1, array $data2): array
{
    return buildDiffIter($data1, $data2);
}

function buildDiffIter(array $data1, array $data2): array
{
    $result1 = array_map(
        static function (string $key1, mixed $value1) use ($data2) {
            if (array_key_exists($key1, $data2)) {
                $value2 = $data2[$key1];

                if (is_array($value1) && is_array($value2)) {
                    return [
                        'compare' => NESTED,
                        'key' => $key1,
                        'value' => buildDiffIter($value1, $value2),
                    ];
                }

                if ($value1 === $value2) {
                    return [
                        'compare' => UNCHANGED,
                        'key' => $key1,
                        'value' => $value1,
                    ];
                }

                return [
                    'compare' => CHANGED,
                    'key' => $key1,
                    'value1' => $value1,
                    'value2' => $value2,
                ];
            }

            return [
                'compare' => DELETED,
                'key' => $key1,
                'value' => $value1,
            ];
        },
        array_keys($data1),
        $data1
    );

    $json2Unique = array_diff_key($data2, $data1);

    $result2 = array_map(
        static function ($key, $value) {
            return [
                'compare' => ADDED,
                'key' => $key,
                'value' => $value,
            ];
        },
        array_keys($json2Unique),
        $json2Unique
    );

    $result = [...$result1, ...$result2];

    return sort($result, static function ($item1, $item2) {
        return $item1['key'] <=> $item2['key'];
    });
}
