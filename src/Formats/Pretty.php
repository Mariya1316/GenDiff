<?php

namespace Gendiff\Formats\Pretty;

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

function genPrettyReport($ast, $nestingLevel = 0)
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
                $value = genPrettyReport($node['children'], $nestingLevel + 1);
                return "{$indent}    {$node['key']}: {$value}{$indent}";
        }
    }, $ast);
    return "{\n" . implode("\n", $result) . "\n{$indent}}";
}
