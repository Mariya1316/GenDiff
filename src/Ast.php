<?php

namespace Gendiff\Ast;

use function Funct\Collection\union;

function genNode($key, $type, $valueBefore, $valueAfter, $children)
{
    return [
        'key' => $key,
        'type' => $type,
        'valueBefore' => $valueBefore,
        'valueAfter' => $valueAfter,
        'children' => $children
    ];
}

function genAst($data1, $data2)
{
    $keyUnion = union(array_keys($data1), array_keys($data2));
    $result = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return genNode($key, 'added', null, $data2[$key], null);
        }
        if (!array_key_exists($key, $data2)) {
            return genNode($key, 'deleted', $data1[$key], null, null);
        }
        if ($data1[$key] === $data2[$key]) {
            return genNode($key, 'unchanged', $data1[$key], $data2[$key], null);
        } else {
            if (is_object($data1[$key]) && is_object($data2[$key])) {
                $dataInArray1 = get_object_vars($data1[$key]);
                $dataInArray2 = get_object_vars($data2[$key]);
                return genNode($key, 'nested', null, null, genAst($dataInArray1, $dataInArray2));
            } else {
                return genNode($key, 'changed', $data1[$key], $data2[$key], null);
            }
        }
    }, $keyUnion);
    return $result;
}
