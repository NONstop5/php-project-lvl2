<?php

declare(strict_types=1);

namespace Tests;

use JsonException;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath = __DIR__ . '/fixtures';

    private function getExpectedPath(string $formatter): string
    {
        return "{$this->fixturesPath}/{$formatter}-expected.txt";
    }

    private function getFirstFilePath(string $fileType): string
    {
        return "{$this->fixturesPath}/file1.{$fileType}";
    }

    private function getSecondFilePath(string $fileType): string
    {
        return "{$this->fixturesPath}/file2.{$fileType}";
    }

    /**
     * @dataProvider dataProvider
     * @throws JsonException
     */
    public function testDiff(
        string $formatter,
        string $firstFileType,
        string $secondFileType
    ): void {
        $diff = genDiff($this->getFirstFilePath($firstFileType), $this->getSecondFilePath($secondFileType), $formatter);

        $this->assertStringEqualsFile($this->getExpectedPath($formatter), $diff);
    }

    public function dataProvider(): array
    {
        return [
            'stylish format, json - json' => [
                'stylish',
                'json',
                'json',
            ],
            'stylish format, yaml - json' => [
                'stylish',
                'yaml',
                'json',
            ],
            'plain format, yaml - yml' => [
                'plain',
                'yaml',
                'yml',
            ],
            'json format, json - json' => [
                'json',
                'json',
                'json',
            ],
            'json format, yaml - yml' => [
                'json',
                'yaml',
                'yml',
            ],
        ];
    }
}
