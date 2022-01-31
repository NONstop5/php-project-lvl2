<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixturesPath = __DIR__ . '/fixtures';
    }

    public function testDiffJson()
    {
        $pathToFile1 = "{$this->fixturesPath}/file1.json";
        $pathToFile2 = "{$this->fixturesPath}/file2.json";
        $expectedData = file_get_contents("{$this->fixturesPath}/expected.txt");

        $diff = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expectedData, $diff);
    }

    public function testDiffYml()
    {
        $pathToFile1 = "{$this->fixturesPath}/file1.yml";
        $pathToFile2 = "{$this->fixturesPath}/file2.yaml";
        $expectedData = file_get_contents("{$this->fixturesPath}/expected.txt");

        $diff = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expectedData, $diff);
    }

    public function testDiffJsonYml()
    {
        $pathToFile1 = "{$this->fixturesPath}/file1.json";
        $pathToFile2 = "{$this->fixturesPath}/file2.yaml";
        $expectedData = file_get_contents("{$this->fixturesPath}/expected.txt");

        $diff = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expectedData, $diff);
    }

    public function testDiffYmlJson()
    {
        $pathToFile1 = "{$this->fixturesPath}/file1.yml";
        $pathToFile2 = "{$this->fixturesPath}/file2.json";
        $expectedData = file_get_contents("{$this->fixturesPath}/expected.txt");

        $diff = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expectedData, $diff);
    }
}
