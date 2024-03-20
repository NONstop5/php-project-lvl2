<?php

declare(strict_types=1);

namespace Differ\Differ;

use JsonException;

use function Differ\DataGetter\getFileData;
use function Differ\Formatter\format;
use function Differ\Parsers\parse;

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

    $dataDiff = getDataDiff($fileData1, $fileData2);

    return format($format, $dataDiff);
}

function getDataDiff(array $data1, array $data2): array
{
    $result = [];

    foreach ($data1 as $key => $value) {
        if (array_key_exists($key, $data2)) {
            if ($data2[$key] === $value) {
                $result[] = [
                    'key' => $key,
                    'value' => $value,
                    'compare' => ' ',
                ];
            } else {
                $result[] = [
                    'key' => $key,
                    'value' => $value,
                    'compare' => '-',
                ];
                $result[] = [
                    'key' => $key,
                    'value' => $data2[$key],
                    'compare' => '+',
                ];
            }
        } else {
            $result[] = [
                'key' => $key,
                'value' => $value,
                'compare' => '-',
            ];
        }
    }

    $json2Unique = array_diff_key($data2, $data1);

    foreach ($json2Unique as $key => $value) {
        $result[] = [
            'key' => $key,
            'value' => $value,
            'compare' => '+',
        ];
    }

    usort(
        $result,
        static function ($item1, $item2) {
            return $item1['key'] <=> $item2['key'];
        }
    );

    return $result;
}
