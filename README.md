# GenDiff
[![Maintainability](https://api.codeclimate.com/v1/badges/1b9f4d3e473ac5d85109/maintainability)](https://codeclimate.com/github/Mariya1316/project-lvl2-s459/maintainability)
[![Build Status](https://travis-ci.org/Mariya1316/GenDiff.svg?branch=master)](https://travis-ci.org/Mariya1316/GenDiff)

A PHP console utility to find differences in configuration files.
### Utility features:
* Multi-format support
* Report generation as plain text, pretty и json
## Installation

Via Composer
```
$ composer require mariya/gendiff
```
## Usage
Get help:
```
$ gendiff -h
```
Generate the difference between two files (`json`, `yaml` or `ini`):
```
$ gendiff before.json after.json
```
The utility supports different output formats. By default, the report is generated as `pretty`. Example:
```
$ gendiff before.json after.json

{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
```
Using the option `--format` with value `plain` to get a report in the following format:
```
$ gendiff --format plain before.json after.json

Property 'timeout' was changed. From '50' to '20'
Property 'proxy' was removed
Property 'verbose' was added with value: 'true'
```
In addition, you can get the report in `json` format using the appropriate option value:
```
$ gendiff --format json before.json after.json

{"0":{"key":"host","type":"unchanged","valueBefore":"hexlet.io","valueAfter":"hexlet.io","children":null},"1":{"key":"timeout","type":"changed","valueBefore":50,"valueAfter":20,"children":null},"2":{"key":"proxy","type":"deleted","valueBefore":"123.234.53.22","valueAfter":null,"children":null},"3":{"key":"verbose","type":"added","valueBefore":null,"valueAfter":true,"children":null}}
```

