<?php

require_once __DIR__ . '/vendor/autoload.php';

use Classes\CsvParser;

$parser = new CsvParser;

if (count($argv) < 3)
    die('No parameters passed' . PHP_EOL);

$allowed_tasks = [];
$allowed_delimiters = [];

if(!isset(CsvParser::$delimiters[$argv[1]]))
    die('Not allowed delimiter' . PHP_EOL);

if(!isset(CsvParser::$tasks[$argv[2]]))
    die('Not allowed task' . PHP_EOL);

$parser->setTask($argv[2])
    ->setDelimiter($argv[1])
    ->run();