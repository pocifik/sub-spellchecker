<?php

namespace models;

class SubParser
{
    protected $file;

    protected const max_size = 1024 * 1024 * 16;
    protected const types = [
        'text/x-ssa',
    ];

    protected $error_message;

    /**
     * SubParser constructor.
     * @param SubFile $file
     * @throws \Exception
     */
    public function __construct($file)
    {
        $this->file = $file;

        if (!$this->validate()) {
            throw new \Exception($this->error_message);
        }
    }

    protected function validate()
    {
        if ($this->file->size > self::max_size) {
            $this->error_message = 'File is too big. Limit 16 MB';
            return false;
        }

        if (!in_array($this->file->type, self::types)) {
            $this->error_message = "Wrong file type. Expect 'ssa/ass (text/x-ssa)'. Given '{$this->file->type}'";
            return false;
        }

        return true;
    }

    public function getContent()
    {
        return file_get_contents($this->file->path);
    }
}