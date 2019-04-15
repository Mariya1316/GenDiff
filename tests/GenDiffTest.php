<?php
namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents("tests/TestFiles/expectedJson-Pretty");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.json", "tests/TestFiles/after.json"));
        
        $expected = file_get_contents("tests/TestFiles/expectedYaml-Pretty");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.yaml", "tests/TestFiles/after.yaml"));
        
        $expected = file_get_contents("tests/TestFiles/expectedJson-Plain");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.json", "tests/TestFiles/after.json", "plain"));
        
        $expected = file_get_contents("tests/TestFiles/expectedYaml-Plain");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.yaml", "tests/TestFiles/after.yaml", "plain"));

        $expected = file_get_contents("tests/TestFiles/expectedYaml-Json");
        $this->assertEquals($expected, genDiff("tests/TestFiles/before.yaml", "tests/TestFiles/after.yaml", "json"));
    }
}
