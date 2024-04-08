<?php

declare(strict_types=1);

namespace Differ\Parser;

use JsonException;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

const FORMAT_JSON = 'json';
const FORMAT_YML = 'yml';
const FORMAT_YAML = 'yaml';

/**
 * @param string $dataFormat
 * @param string $data
 *
 * @return array
 * @throws JsonException
 */
function parse(string $dataFormat, string $data): array
{
    switch ($dataFormat) {
        case FORMAT_JSON:
            return jsonFileParse($data);
        case FORMAT_YML:
        case FORMAT_YAML:
            return yamlFileParse($data);
        default:
            throw new RuntimeException(sprintf('Unknown data format: %s!', $dataFormat));
    }
}

/**
 * @param string $data
 *
 * @return array
 */
function yamlFileParse(string $data): array
{
    return Yaml::parse($data);
    //return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
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
