<?php

namespace models;

use core\Core;

class SubParser
{
    protected $file;

    const max_size = 1024 * 1024 * 16;
    const types = [
        'text/x-ssa',
    ];
    const extension = [
        'ass',
        'sass'
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
        if (empty($this->file)) {
            $this->error_message = 'No file given';
            return false;
        }

        if ($this->file->size > self::max_size) {
            $this->error_message = 'File is too big. Limit 16 MB';
            return false;
        }

        if (!in_array($this->file->extension, self::extension)) {
            $this->error_message = "Wrong file type. Expect 'ssa/ass'. Given '{$this->file->extension}'";
            return false;
        }

        return true;
    }

    public function parse()
    {
        $content = file($this->file->path);

        $dialogues = [];
        foreach ($content as $key => $line) {
            if (substr($line, 0, 9) === 'Dialogue:')
            {
                $dialogues[$key] = $line;
            }
        }

        $texts = [];
        foreach ($dialogues as $key => $dialogue) {
            preg_match('/^Dialogue:(?:[^,]*,){9}(.+)/', $dialogue, $matches, PREG_OFFSET_CAPTURE);
            $text = $matches[1];
            if (preg_match('/{.*\\\p[1-9].*}/', $text[0]))
                continue;
            $text_formatted = preg_replace('/\\\N/', ' {\N}', $text[0]);
            $texts[$key] = $text_formatted;
            $dialogues[$key] = [
                'text' => $dialogues[$key],
                'offset' => $text[1]
            ];
        }

        $final_text = '';
        foreach ($texts as $key => $text) {
            $final_text .= "[\d$key]" . $text;
        }

        $matches = LanguagetoolAPI::sendRequest($final_text);

        foreach ($matches as $key => $match) {
            $matches[$key]['tags'] = $texts[$key];
        }

        Core::$session->set('matches', $matches);
        Core::$session->set('content', $content);
        Core::$session->set('dialogues', $dialogues);

        return $matches;

    }

    public function getContent()
    {
        return file_get_contents($this->file->path);
    }
}