<?php
namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\Differ\genDiff;

class JsonTest extends TestCase
{
    public function testJson()
    {
        $expected = "{\n    host: hexlet.io\n  - proxy: 123.234.53.22\n  + timeout: 20\n  - timeout: 50\n  + verbose: 1\n}\n";
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.json", "tests/TestFiles/after.json"));
        
        $expected = "{\n  + key: value\n    name: mariya/gendiff\n  - some-kay: some-value\n  - type: library\n  + type: project\n}\n";
        $this->assertEquals($expected, genDiff("tests/TestFiles/before-2.json", "tests/TestFiles/after-2.json"));
    }
}
