<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testDiff()
    {
        $fixturesPath = __DIR__ . '/fixtures';
        $pathToFile1 = "{$fixturesPath}/file1.json";
        $pathToFile2 = "{$fixturesPath}/file2.json";
        $expectedData = file_get_contents("{$fixturesPath}/expected.txt");

        $diff = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expectedData, $diff);
    }
}
