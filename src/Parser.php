<?php

namespace Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $dataType)
{
    switch ($dataType) {
        case 'json':
            $resultDecode = json_decode($data);
            break;
        case 'yaml':
            $resultDecode = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new \Exception('Parsing is only possible for data type: json, yaml');
    }
    if ($resultDecode === null) {
        throw new \Exception('Decoding error, recheck data');
    } else {
        return $resultDecode;
    }
}
