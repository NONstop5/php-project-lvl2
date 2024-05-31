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
    return [
        'type' => 'root',
        'children' => buildDiffIter($data1, $data2),
    ];
}

function buildDiffIter(array $data1, array $data2): array
{
    $result = array_map(
        static function ($key, $value) use ($data2) {
            if (array_key_exists($key, $data2)) {
                //if (is_array($data2[$key])) {
                //
                //}
                if ($data2[$key] === $value) {
                    return [
                        'key' => $key,
                        'value' => $value,
                        'compare' => UNCHANGED,
                    ];
                } else {
                    return [
                        [
                        'key' => $key,
                        'value' => $value,
                        'compare' => DELETED,
                        ],
                        [
                            'key' => $key,
                            'value' => $data2[$key],
                            'compare' => ADDED,
                        ]
                    ];
                }
            } else {
                return [
                    'key' => $key,
                    'value' => $value,
                    'compare' => DELETED,
                ];
            }
        },
        array_keys($data1),
        $data1
    );

    //return $result;
    $json2Unique = array_diff_key($data2, $data1);

    $result = array_map(
        static function ($key, $value) {
            return [
                'key' => $key,
                'value' => $value,
                'compare' => DELETED,
            ];
        },
        array_keys($json2Unique),
        $json2Unique
    );

    $sortedResult = sort($result, static function ($item1, $item2) {
        return $item1['key'] <=> $item2['key'];
    });

    //usort(
    //    $result,
    //    static function ($item1, $item2) {
    //        return $item1['key'] <=> $item2['key'];
    //    }
    //);

    return $sortedResult;

}
