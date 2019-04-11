<?php

namespace Gendiff\Differ;

use function Gendiff\Parser\parse;
use function Funct\Collection\intersection;

function checkFile($pathToFile)
{
    if (file_exists($pathToFile)) {
        if (is_readable($pathToFile)) {
            return true;
        } else {
            throw new \Exception("File {$pathToFile} is not readable");
        }
    } else {
        throw new \Exception("File {$pathToFile} does not exist");
    }
}

function compareData($data)
{
    $unchangedData = array_intersect_assoc($data[0], $data[1]);
    $addedData = array_diff_key($data[1], $data[0]);
    $deletedData = array_diff_key($data[0], $data[1]);
    $changedDataBefore = array_diff_key(array_intersect_key($data[0], $data[1]), $unchangedData);
    $changedDataAfter = array_diff_key(array_intersect_key($data[1], $data[0]), $unchangedData);

    $unchangedDataInString = array_map(function ($key, $value) {
        return "    {$key}: {$value}";
    }, array_keys($unchangedData), array_values($unchangedData));
        
    $addedDataInString = array_map(function ($key, $value) {
        return "  + {$key}: {$value}";
    }, array_keys($addedData), array_values($addedData));

    $deletedDataInString = array_map(function ($key, $value) {
        return "  - {$key}: {$value}";
    }, array_keys($deletedData), array_values($deletedData));
    
    $changedDataBeforeInString = array_map(function ($key, $value) {
        return "  - {$key}: {$value}";
    }, array_keys($changedDataBefore), array_values($changedDataBefore));
    
    $changedDataAfterInString = array_map(function ($key, $value) {
        return "  + {$key}: {$value}";
    }, array_keys($changedDataAfter), array_values($changedDataAfter));

    $result = array_merge(
        $unchangedDataInString,
        $changedDataBeforeInString,
        $changedDataAfterInString,
        $deletedDataInString,
        $addedDataInString
    );
    array_unshift($result, '{');
    $result[] = "}\n";

    return implode("\n", $result);
}

function genDiff($filePath1, $filePath2)
{
    $filePaths = [$filePath1, $filePath2];
    $dataType = 'json';
    try {
        $data = array_map(function ($filePath) use ($dataType) {
            $resultCheck = checkFile($filePath);
            $fileContent = file_get_contents($filePath);
            return parse($fileContent, $dataType);
        }, $filePaths);
        return compareData($data);
    } catch (\Exception $e) {
        echo $e->getMessage(), "\n";
    }
}
