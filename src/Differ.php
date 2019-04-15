<?php

namespace Gendiff\Differ;

use function Gendiff\Parser\parse;
use function Gendiff\Ast\genAst;
use function Gendiff\Formats\Pretty\genPrettyReport;
use function Gendiff\Formats\Plain\genPlainReport;

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

function genDiff($filePath1, $filePath2, $reportFormat = 'pretty')
{
    $dataType = checkFiles($filePath1, $filePath2);
    $fileContent1 = file_get_contents($filePath1);
    $fileContent2 = file_get_contents($filePath2);
    $data1 = get_object_vars(parse($fileContent1, $dataType));
    $data2 = get_object_vars(parse($fileContent2, $dataType));
    $ast = genAst($data1, $data2);
    $result = ($reportFormat === 'plain') ? genPlainReport($ast) : genPrettyReport($ast);
    return "{$result}\n";
}
