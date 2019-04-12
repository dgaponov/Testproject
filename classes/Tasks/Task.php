<?php

namespace Classes\Tasks;

use Classes\User;

abstract class Task
{

    const MAX_TEXTS_INDEX = 1000;

    protected $texts_path;

    /** @var User */
    protected $user;
    protected $count_files = 0;

    public function __construct(string $texts_path = './texts')
    {
        $this->texts_path = $texts_path;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function generateFileName(string $text_user_path, int $i)
    {
        return $text_user_path . '-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.txt';
    }

    public function parseUserFiles()
    {
        // прогоняем все файлы этого пользователя
        $text_user_path = $this->texts_path . '/' . $this->user->getId();

        // по тз не было указания, идут ли файлы с очередной нумерацией или могут быть пропуски
        // поэтому оставил прогон по циклу
        for ($i = 0; $i < static::MAX_TEXTS_INDEX; $i++) {
            if (file_exists($filepath = $this->generateFileName($text_user_path, $i))) {
                $this->count_files++;
                $this->processFile($filepath);
            }
        }
    }

    public function execute(User $user)
    {
        $this->setUser($user);
        $this->resetState();
        $this->parseUserFiles();
        $this->result();
    }

    public function resetState()
    {
        $this->count_files = 0;
    }

    abstract public function processFile(string $filepath);
    abstract public function result();

}