<?php

namespace Gendiff\Formats\Plain;

function stringify($data)
{
    return is_object($data) ? 'complex value' : json_encode($data);
}

function genPlainReport($ast, $ancestors = '')
{
    $result = array_reduce($ast, function ($acc, $node) use ($ancestors) {
        $valueBefore = stringify($node['valueBefore']);
        $valueAfter = stringify($node['valueAfter']);
        $ancestors = "{$ancestors}{$node['key']}";
        switch ($node['type']) {
            case 'added':
                $acc[] = "Property '{$ancestors}' was added with value: '{$valueAfter}'";
                break;
            case 'deleted':
                $acc[] = "Property '{$ancestors}' was removed";
                break;
            case 'changed':
                $acc[] = "Property '{$ancestors}' was changed. From '{$valueBefore}' to '{$valueAfter}'";
                break;
            case 'nested':
                $acc[] = genPlainReport($node['children'], "{$ancestors}.");
                break;
        }
        return $acc;
    }, []);
    return implode("\n", $result);
}
