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
            $text_formatted = preg_replace('/\\\N/', '{\N}', $text[0]);
            //$text_formatted = preg_split('/({.*?})/', $text_formatted, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);
            $texts[$key] = $text_formatted;
            $dialogues[$key] = [
                'text' => $dialogues[$key],
                'offset' => $text[1]
            ];
        }



        /*

        $final_text = '';

        foreach ($texts as $key => $text) {
            foreach ($text[2] as $item) {
                if ($item[0][0] != '{') {
                    $final_text .= '{l'.$key.'}{o'.$text[1].'}' . $item[0];
                }
            }
        }

        //print_r($final_text);

        $api = LanguagetoolAPI::sendRequest($final_text);

        foreach ($api as $item) {
            if (preg_match('/{l(\d+)}{o(\d+)}/', $item['text'], $matches)) {
                $text = $item['text'];
                //$text =  preg_replace('/{l(\d+)}{o(\d+)}/', '', $item['text']);
                $line = $matches[1];
                $offset = $matches[2];

                $final_text = $this->mb_substr_replace($final_text, $item['replace']['value'], $item['offset'], $item['length']);
                //$dialogues[$line] = substr_replace($dialogues[$line], $text, $offset);
            }
        }

        preg_match_all('/{l(\d+)}{o(\d+)}(.+)/', $final_text, $matches);

        print_r($matches);
        die();

        */

        $final_text = '';
        $count = 0;
        foreach ($texts as $key => $text) {
            $count++;
            if ($count > 1000)
                break;
            //foreach ($text as $item) {
                //if ($item[0][0] != '{') {
                    $final_text .= "[\d$key]" . $text;
                //}
            //}
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