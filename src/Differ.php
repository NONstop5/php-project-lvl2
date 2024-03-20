<?php

declare(strict_types=1);

namespace Differ\Differ;

use JsonException;
use RuntimeException;

use function Differ\Parsers\parse;

const FILE_FORMAT_JSON = 'json';
const FILE_FORMAT_YAML = 'yaml';
const FILE_EXTENSION_JSON = 'json';
const FILE_EXTENSIONS_YAML = ['yml', 'yaml'];

/**
 * @param string $pathToFile1
 * @param string $pathToFile2
 * @param string $format
 *
 * @return string
 * @throws JsonException
 */
function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    [
        'fileDataType' => $file1DataType,
        'fileRawData' => $file1RawData,
    ] = getFileData($pathToFile1);
    [
        'fileDataType' => $file2DataType,
        'fileRawData' => $file2RawData,
    ] = getFileData($pathToFile2);

    $fileData1 = parse($file1DataType, $file1RawData);
    $fileData2 = parse($file2DataType, $file2RawData);

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

function getFileData(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new RuntimeException(sprintf('File on path "%s" not found!', $filePath));
    }

    return [
        'fileDataType' => getFileFormat($filePath),
        'fileRawData' => file_get_contents($filePath),
    ];
}

function getFileFormat(string $filePath): string
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($fileExtension === FILE_EXTENSION_JSON) {
        return FILE_FORMAT_JSON;
    }

    if (in_array($fileExtension, FILE_EXTENSIONS_YAML, true)) {
        return FILE_FORMAT_YAML;
    }

    throw new RuntimeException('Wrong file format!');
}

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
