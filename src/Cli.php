<?php

declare(strict_types=1);

namespace Differ\Cli;

use Docopt;

function getInput(): array
{
    $doc = getDoc();
    $args = Docopt::handle($doc, ['version' => '1.0']);

    $pathToFile1 = $args['<firstFile>'];
    $pathToFile2 = $args['<secondFile>'];
    $format = $args['--format'];

    return [
        'pathToFile1' => $pathToFile1,
        'pathToFile2' => $pathToFile2,
        'format' => $format,
    ];
}

function getDoc(): string
{
    return <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen.
  -v --version                  Show version.
  --format <fmt>                Report format [default: stylish]
DOC;
}
