<?php

declare(strict_types=1);

namespace Differ\DataGetter;

use RuntimeException;

const SUPPORTED_FILE_EXTENSIONS = [
    'json',
    'yml',
    'yaml'
];

function getFileData(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new RuntimeException(sprintf('File on path "%s" not found!', $filePath));
    }

    return [
        'dataFormat' => getFileFormat($filePath),
        'rawData' => file_get_contents($filePath),
    ];
}

function getFileFormat(string $filePath): string
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    if (in_array($fileExtension, SUPPORTED_FILE_EXTENSIONS, true)) {
        return $fileExtension;
    }

    throw new RuntimeException('Only Json and Yaml files are supported!');
}
