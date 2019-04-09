<?php

namespace Gendiff\Differ;

use function Gendiff\Parser\pars;
use function Funct\Collection\merge;

function checkFile($pathToFile)
{
    if (file_exists($pathToFile)) {
        if (is_readable($pathToFile)) {
            return true;
        } else {
            return "File {$pathToFile} is not readable";
        }
    } else {
        return "File {$pathToFile} does not exist";
    }
}

function compareData($data)
{
    $unchangedData = array_intersect_assoc($data[0], $data[1]);
    $unchangedDataNew = array_map(function ($key, $value) {
        return "{$key}: {$value}    ";
    }, array_keys($unchangedData), array_values($unchangedData));
    
    $addedData = array_diff_assoc($data[1], $data[0]);
    $addedDataNew = array_map(function ($key, $value) {
        return "{$key}: {$value}  + ";
    }, array_keys($addedData), array_values($addedData));

    $deletedAndChangedData = array_diff_assoc($data[0], $data[1]);
    $deletedAndChangedDataNew = array_map(function ($key, $value) {
        return "{$key}: {$value}  - ";
    }, array_keys($deletedAndChangedData), array_values($deletedAndChangedData));
    
    $mergedData = [];
    merge($mergedData, $unchangedDataNew, $addedDataNew, $deletedAndChangedDataNew);
    sort($mergedData);
    $result = array_map(function ($string) {
        return substr($string, strlen($string) - 4, 4) . substr($string, 0, strlen($string) - 4);
    }, $mergedData);
    array_unshift($result, '{');
    $result[] = "}\n";

    return implode("\n", $result);
}

function genDiff($pathToFile1, $pathToFile2)
{
    $files = [$pathToFile1, $pathToFile2];
    $dataType = 'json';
    $data = [];
    foreach ($files as $file) {
        $resultCheck = checkFile($file);
        if ($resultCheck) {
            $fileContent = file_get_contents($file);
            $parsingResult = pars($fileContent, $dataType);
            if (!is_array($parsingResult)) {
                return $parsingResult;
            } else {
                $data[] = $parsingResult;
            }
        } else {
            return $resultCheck;
        }
    }
    return compareData($data);
}
