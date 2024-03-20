<?php

declare(strict_types=1);

namespace Differ\Parsers;

use JsonException;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

use const Differ\Differ\FILE_FORMAT_JSON;
use const Differ\Differ\FILE_FORMAT_YAML;

/**
 * @param string $dataType
 * @param string $data
 *
 * @return array
 * @throws JsonException
 */
function parse(string $dataType, string $data): array
{
    switch ($dataType) {
        case FILE_FORMAT_JSON:
            return jsonFileParse($data);
        case FILE_FORMAT_YAML:
            return yamlFileParse($data);
        default:
            throw new RuntimeException('Unknown file format!');
    }
}

/**
 * @param string $yamlFilePath
 *
 * @return array
 */
function yamlFileParse(string $yamlFilePath): array
{
    return Yaml::parse($yamlFilePath);
}

/**
 * @param string $data
 *
 * @return array
 * @throws JsonException
 */
function jsonFileParse(string $data): array
{
    return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
}
