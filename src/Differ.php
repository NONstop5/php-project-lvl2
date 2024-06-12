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
    $uniqueKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = sort($uniqueKeys, static function ($key1, $key2) {
        return $key1 <=> $key2;
    });

    return array_map(
        static function (string $key) use ($data1, $data2) {
            $value1 = $data1[$key] ?? null;
            $value2 = $data2[$key] ?? null;

            if (
                (is_array($value1) && !array_is_list($value1)) &&
                (is_array($value2) && !array_is_list($value2))
            ) {
                return [
                    'compare' => NESTED,
                    'key' => $key,
                    'value' => buildDiffIter($value1, $value2),
                ];
            }

            if (!array_key_exists($key, $data1)) {
                return [
                    'compare' => ADDED,
                    'key' => $key,
                    'value' => $value2,
                ];
            }

            if (!array_key_exists($key, $data2)) {
                return [
                    'compare' => DELETED,
                    'key' => $key,
                    'value' => $value1,
                ];
            }

            if ($value1 === $value2) {
                return [
                    'compare' => UNCHANGED,
                    'key' => $key,
                    'value' => $value1,
                ];
            }

            return [
                'compare' => CHANGED,
                'key' => $key,
                'value1' => $value1,
                'value2' => $value2,
            ];
        },
        $sortedKeys
    );
}
