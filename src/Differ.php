<?php

namespace Gendiff\Differ;

use function Gendiff\Parser\parse;
use function Funct\Collection\union;

function checkFile($pathToFile)
{
    if (!file_exists($pathToFile)) {
        throw new \Exception("File {$pathToFile} does not exist");
    }
    if (!is_readable($pathToFile)) {
        throw new \Exception("File {$pathToFile} is not readable");
    }
}

function compareData($data1, $data2)
{
    $keyUnion = union(array_keys($data1), array_keys($data2));
    $result = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return "  + {$key}: {$data2[$key]}";
        }
        if (!array_key_exists($key, $data2)) {
            return "  - {$key}: {$data1[$key]}";
        }
        if ($data1[$key] !== $data2[$key]) {
            return "  - {$key}: {$data1[$key]}\n  + {$key}: {$data2[$key]}";
        }
        if ($data1[$key] === $data2[$key]) {
            return "    {$key}: {$data1[$key]}";
        }
    }, $keyUnion);
    return "{\n" . implode("\n", $result) . "\n}\n";
}

function genDiff($filePath1, $filePath2)
{
    $dataType = 'json';
    checkFile($filePath1);
    checkFile($filePath2);
    $fileContent1 = file_get_contents($filePath1);
    $fileContent2 = file_get_contents($filePath2);
    $data1 = parse($fileContent1, $dataType);
    $data2 = parse($fileContent2, $dataType);
    return compareData($data1, $data2);
}
