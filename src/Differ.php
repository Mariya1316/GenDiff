<?php

namespace Gendiff\Differ;

use function Gendiff\Parser\parse;
use function Gendiff\Ast\genAst;
use function Funct\Collection\flatten;

function checkFiles($filePath1, $filePath2)
{
    if (!is_readable($filePath1)) {
        throw new \Exception("File {$filePath1} is not readable or does not exist");
    }
    if (!is_readable($filePath2)) {
        throw new \Exception("File {$filePath2} is not readable or does not exist");
    }
    if (pathinfo($filePath1, PATHINFO_EXTENSION) !== pathinfo($filePath2, PATHINFO_EXTENSION)) {
        throw new \Exception("Files have different types");
    } else {
        return pathinfo($filePath1, PATHINFO_EXTENSION);
    }
}

function stringify($data, $nestingLevel)
{
    return is_object($data) ? getNestedData($data, $nestingLevel) : json_encode($data);
}

function getNestedData($dataInObject, $nestingLevel)
{
    $indent = str_repeat(' ', 4 * $nestingLevel);
    $dataInArray = get_object_vars($dataInObject);
    $result = array_map(function ($key) use ($nestingLevel, $dataInArray) {
        $indent = str_repeat(' ', 4 * ($nestingLevel + 1));
        $value = stringify($dataInArray[$key], $nestingLevel + 1);
        return "{$indent}{$key}: {$value}";
    }, array_keys($dataInArray));
    return "{\n" . implode("\n", $result) . "\n{$indent}}";
}

function genResultStructure($ast, $nestingLevel)
{
    $indent = str_repeat(' ', 4 * $nestingLevel);
    $result = array_map(function ($node) use ($nestingLevel) {
        $indent = str_repeat(' ', 4 * $nestingLevel);
        $valueBefore = stringify($node['valueBefore'], $nestingLevel + 1);
        $valueAfter = stringify($node['valueAfter'], $nestingLevel + 1);
        switch ($node['type']) {
            case 'added':
                return "{$indent}  + {$node['key']}: {$valueAfter}";
            case 'deleted':
                return "{$indent}  - {$node['key']}: {$valueBefore}";
            case 'unchanged':
                return "{$indent}    {$node['key']}: {$valueBefore}";
            case 'changed':
                return "{$indent}  + {$node['key']}: {$valueBefore}\n{$indent}  - {$node['key']}: {$valueAfter}";
            case 'nested':
                $value = genResultStructure($node['children'], $nestingLevel + 1);
                return "{$indent}    {$node['key']}: {$value}{$indent}";
        }
    }, $ast);
    return "{\n" . implode("\n", $result) . "\n{$indent}}";
}

function genDiff($filePath1, $filePath2)
{
    $dataType = checkFiles($filePath1, $filePath2);
    $fileContent1 = file_get_contents($filePath1);
    $fileContent2 = file_get_contents($filePath2);
    $data1 = get_object_vars(parse($fileContent1, $dataType));
    $data2 = get_object_vars(parse($fileContent2, $dataType));
    $ast = genAst($data1, $data2);
    $result = genResultStructure($ast, 0);
    return $result;
}
