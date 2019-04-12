<?php

namespace Classes\Tasks;

class CountAverageLineCountTask extends Task
{

    private $count_lines = 0;

    public function resetState()
    {
        parent::resetState();
        $this->count_lines = 0;
    }

    public function processFile(string $filepath)
    {
        $this->count_lines += count(file($filepath));
    }

    public function result()
    {
        $avg_lines = $this->count_files ? $this->count_lines / $this->count_files : 0;
        echo $this->getUser()->getName() . ' | AVG Lines = ' . $avg_lines . PHP_EOL;
    }

}