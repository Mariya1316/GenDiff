<?php

namespace Gendiff\Differ;

use function Gendiff\Parser\parse;
use function Gendiff\Ast\genAst;
use function Funct\Collection\flatten;

function checkFiles($filePath1, $filePath2)
{
    if (!file_exists($filePath1)) {
        throw new \Exception("File {$filePath1} does not exist");
    }
    if (!file_exists($filePath2)) {
        throw new \Exception("File {$filePath2} does not exist");
    }
    if (!is_readable($filePath1)) {
        throw new \Exception("File {$filePath1} is not readable");
    }
    if (!is_readable($filePath2)) {
        throw new \Exception("File {$filePath2} is not readable");
    }
    if (pathinfo($filePath1, PATHINFO_EXTENSION) !== pathinfo($filePath2, PATHINFO_EXTENSION)) {
        throw new \Exception("Files have different types");
    } else {
        return pathinfo($filePath1, PATHINFO_EXTENSION);
    }
}

function getNestedData($dataInObject, $nestingLevel)
{
    $indent = str_repeat(' ', 4 * $nestingLevel);
    $dataInArray = get_object_vars($dataInObject);
    $result = array_map(function ($key) use ($nestingLevel, $dataInArray) {
        $indent = str_repeat(' ', 4 * ($nestingLevel + 1));
        if (is_object($dataInArray[$key])) {
            $value = getNestedData($dataInArray[$key], $nestingLevel + 1);
        } else {
            $value = $dataInArray[$key];
        }
        return "{$indent}{$key}: {$value}";
    }, array_keys($dataInArray));
    return "{\n" . implode("\n", $result) . "\n{$indent}}";
}

function genResultStructure($ast, $nestingLevel)
{
    $indent = str_repeat(' ', 4 * $nestingLevel);
    $result = array_map(function ($node) use ($nestingLevel) {
        $indent = str_repeat(' ', 4 * $nestingLevel);
        $valueBefore = is_object($node['valueBefore']) ?
            getNestedData($node['valueBefore'], $nestingLevel + 1) : json_encode($node['valueBefore']);
        $valueAfter = is_object($node['valueAfter']) ?
            getNestedData($node['valueAfter'], $nestingLevel + 1) : json_encode($node['valueAfter']);
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
                return "{$indent}    {$node['key']}: {$value}";
        }
    }, $ast);
    return "{\n" . implode("\n", $result) . "\n{$indent}}";
}

function genDiff($filePath1, $filePath2)
{
    $dataType = checkFiles($filePath1, $filePath2);
    $fileContent1 = file_get_contents($filePath1);
    $fileContent2 = file_get_contents($filePath2);
    $parsingResult1 = get_object_vars(parse($fileContent1, $dataType));
    $parsingResult2 = get_object_vars(parse($fileContent2, $dataType));
    $ast = genAst($parsingResult1, $parsingResult2);
    $result = genResultStructure($ast, 0);
    return $result;
}
