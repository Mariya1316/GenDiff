<?php
namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents("tests/TestFiles/expectedJson");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.json", "tests/TestFiles/after.json"));
        
        $expected = file_get_contents("tests/TestFiles/expectedYaml");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.yaml", "tests/TestFiles/after.yaml"));
        
        $expected = file_get_contents("tests/TestFiles/expectedJsonPlain");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.json", "tests/TestFiles/after.json", "plain"));
        
        $expected = file_get_contents("tests/TestFiles/expectedYamlPlain");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.yaml", "tests/TestFiles/after.yaml", "plain"));
    }
}
