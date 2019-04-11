<?php
namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\Differ\genDiff;

class JsonTest extends TestCase
{
    public function testJson()
    {
        $expected = file_get_contents("tests/TestFiles/expected");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.json", "tests/TestFiles/after.json"));
        
        $expected = file_get_contents("tests/TestFiles/expected-2");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before-2.json", "tests/TestFiles/after-2.json"));
    }
}
