<?php

namespace Gendiff\Parser;

function pars($data, $dataType)
{
    if ($dataType = 'json') {
        $resultDecode = json_decode($data, true);
        if ($resultDecode === null) {
            return 'Decoding error, recheck json data';
        }
        return $resultDecode;
    } else {
        return 'Parsing is only possible for data type "json"';
    }
}
