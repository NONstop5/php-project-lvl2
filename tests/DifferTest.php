<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $json1;
    private string $json2;
    private string $yml1;
    private string $yaml2;
    private string $expectedData;

    protected function setUp(): void
    {
        parent::setUp();

        $fixturesPath = __DIR__ . '/fixtures/plain';
        $this->json1 = "{$fixturesPath}/file1.json";
        $this->json2 = "{$fixturesPath}/file2.json";
        $this->yml1 = "{$fixturesPath}/file1.yml";
        $this->yaml2 = "{$fixturesPath}/file2.yaml";
        /** @phpstan-ignore-next-line */
        $this->expectedData = file_get_contents("{$fixturesPath}/expected.txt");
    }

    public function testDiffJson(): void
    {
        $diff = genDiff($this->json1, $this->json2);

        $this->assertEquals($this->expectedData, $diff);
    }

    public function testDiffYaml(): void
    {
        $diff = genDiff($this->yml1, $this->yaml2);

        $this->assertEquals($this->expectedData, $diff);
    }

    public function testDiffJsonYaml(): void
    {
        $diff = genDiff($this->json1, $this->yaml2);

        $this->assertEquals($this->expectedData, $diff);
    }

    public function testDiffYamlJson(): void
    {
        $diff = genDiff($this->yml1, $this->json2);

        $this->assertEquals($this->expectedData, $diff);
    }
}
