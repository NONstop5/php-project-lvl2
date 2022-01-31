<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @param string $yamlFilePath
 *
 * @return array
 */
function yamlFileParse(string $yamlFilePath): array
{
    return Yaml::parseFile($yamlFilePath);
}

/**
 * @param string $jsonFilePath
 *
 * @return array
 */
function jsonFileParse(string $jsonFilePath): array
{
    return json_decode(file_get_contents($jsonFilePath), true);
}
