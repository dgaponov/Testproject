<?php

namespace Classes;

use Classes\Tasks\CountAverageLineCountTask;
use Classes\Tasks\ReplaceDatesTask;
use Classes\Tasks\Task;
use ParseCsv\Csv;

class CsvParser
{

    private $people_filepath;
    private $texts_path;
    private $delimiter = ',';
    private $task;

    /** @var Task */
    private $task_strategy;

    public static $delimiters = [
        'comma' => ',',
        'semicolon' => ';',
    ];

    public static $tasks = [
        'countAverageLineCount' => CountAverageLineCountTask::class,
        'replaceDates' => ReplaceDatesTask::class,
    ];

    /**
     * CsvParser constructor.
     * @param string $people_filepath
     * @param string $texts_path
     * @throws \Exception
     */
    public function __construct(string $people_filepath = './people.csv', string $texts_path = './texts')
    {
        if(!file_exists($people_filepath))
            throw new \Exception($people_filepath . ' doesnt exist');

        $this->people_filepath = $people_filepath;
        $this->texts_path = $texts_path;
    }

    public function setDelimiter(string $delimiter)
    {
        if (!array_key_exists($delimiter, static::$delimiters))
            throw new \InvalidArgumentException($delimiter . ' not allowed');
        $this->delimiter = static::$delimiters[$delimiter];
        return $this;
    }

    public function setTask(string $task)
    {
        if (!array_key_exists($task, static::$tasks))
            throw new \InvalidArgumentException($task . ' not allowed');
        $this->task = $task;
        // выставляем стратегию для таска
        $this->task_strategy = new static::$tasks[$task];
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        if(!$this->task_strategy)
            throw new \Exception('Strategy not set');

        foreach($this->getPeopleData() as $user) {
            $this->task_strategy->execute($user);
        }
    }

    /**
     * @return User[]
     */
    public function getPeopleData()
    {
        $csv_reader = new Csv();
        $csv_reader->heading = false;
        $csv_reader->delimiter = $this->delimiter;
        $csv_reader->parse($this->people_filepath);
        return array_map(function($value) {
            if(count($value) < 2)
                throw new \Exception('Invalid '.$this->people_filepath.' structure');
            return new User($value[0], $value[1]);
        }, $csv_reader->data);
    }

}