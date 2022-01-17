<?php

declare(strict_types=1);

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $json1 = json_decode(file_get_contents($pathToFile1), true);
    $json2 = json_decode(file_get_contents($pathToFile2), true);

    $result = [];

    foreach ($json1 as $key => $value) {
        if (array_key_exists($key, $json2)) {
            if ($json2[$key] === $value) {
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
                    'value' => $json2[$key],
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

    $json2Unique = array_diff_key($json2, $json1);

    foreach ($json2Unique as $key => $value) {
        $result[] = [
            'key' => $key,
            'value' => $value,
            'compare' => '+',
        ];
    }

    usort($result, function ($item1, $item2) {
        return $item1['key'] <=> $item2['key'];
    });

    $openBrace = "{\n";
    $closeBrace = "}\n";

    return $openBrace . array_reduce($result, function ($acc, $item) {
        $value = var_export($item['value'], true);

        return $acc . "  {$item['compare']} {$item['key']}: {$value}\n";
    }, '') . $closeBrace;
}

print_r(genDiff('file1.json', 'file2.json'));
