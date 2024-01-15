<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;
use RuntimeException;

use function Differ\Parsers\jsonFileParse;
use function Differ\Parsers\yamlFileParse;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $result = [];

    try {
        $fileData1 = getFileData($pathToFile1);
        $fileData2 = getFileData($pathToFile2);
    } catch (Exception $e) {
        return "Incorrect files!\n";
    }

    foreach ($fileData1 as $key => $value) {
        if (array_key_exists($key, $fileData2)) {
            if ($fileData2[$key] === $value) {
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
                    'value' => $fileData2[$key],
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

    $json2Unique = array_diff_key($fileData2, $fileData1);

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

    $openBrace = "{\n";
    $closeBrace = "}\n";
    $result = array_reduce(
        $result,
        static function ($acc, $item) {
            $value = var_export($item['value'], true);

            return $acc . "  {$item['compare']} {$item['key']}: {$value}\n";
        },
        ''
    );

    return "{$openBrace}{$result}{$closeBrace}";
}

/**
 * @param string $filePath
 *
 * @return array
 * @throws Exception
 */
function getFileData(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new RuntimeException("File {$filePath} not found!");
    }

    $fileFormat = getFileFormat($filePath);

    return $fileFormat === 'json' ? jsonFileParse($filePath) : yamlFileParse($filePath);
}

/**
 * @param string $filePath
 *
 * @return string
 * @throws Exception
 */
function getFileFormat(string $filePath): string
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($fileExtension === 'json') {
        return 'json';
    }

    if ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        return 'yaml';
    }

    throw new RuntimeException('Wrong file format!');
}
