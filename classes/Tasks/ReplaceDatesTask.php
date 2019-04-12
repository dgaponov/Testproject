<?php

namespace Classes\Tasks;

class ReplaceDatesTask extends Task
{

    const REPLACE_PATH = './output_texts';

    private $count_replaces = 0;

    public function resetState()
    {
        parent::resetState();
        $this->count_replaces = 0;
    }

    public function processFile(string $filepath)
    {
        $text = file_get_contents($filepath);
        $replaced_text = preg_replace_callback(
            '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2})/',
            function ($match) {
                return $match[2] . '-' . $match[1] . '-' . ($match[3] >= 50  ? '19' : '20') . $match[3];
            },
            $text,
            -1,
            $count
        );
        file_put_contents(static::REPLACE_PATH . '/' . basename($filepath), $replaced_text);
        $this->count_replaces += $count;
    }

    public function result()
    {
        echo $this->getUser()->getName() . ' | Count replaces = ' . $this->count_replaces . PHP_EOL;
    }

}