<?php

namespace Gendiff\Parser;

function parse($data, $dataType)
{
    if ($dataType === 'json') {
        $resultDecode = json_decode($data, true);
        if ($resultDecode === null) {
            throw new \Exception('Decoding error, recheck json data');
        }
        return $resultDecode;
    } else {
        throw new \Exception('Parsing is only possible for data type json');
    }
}
