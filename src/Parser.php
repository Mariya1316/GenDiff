<?php

namespace Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function mapArraysInString($resultDecode)
{
    $result = array_map(function ($item) {
        if (is_array($item)) {
            return '[' . implode(', ', $item) . ']';
        }
        if (is_object($item)) {
            return json_encode(get_object_vars($item));
        }
        return $item;
    }, $resultDecode);
    return $result;
}

function parse($data, $dataType)
{
    switch ($dataType) {
        case 'json':
            $resultDecode = get_object_vars(json_decode($data));
            $result = mapArraysInString($resultDecode);
            break;
        case 'yaml':
            $resultDecode = get_object_vars(Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP));
            $result = mapArraysInString($resultDecode);
            break;
        default:
            throw new \Exception('Parsing is only possible for data type: json, yaml');
    }
    if ($result === null) {
        throw new \Exception('Decoding error, recheck data');
    } else {
        return $result;
    }
}
