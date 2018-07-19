<?php

namespace controllers;

use core\Core;
use core\base_classes\BaseController;
use core\helpers\StringHelper;
use models\SubFile;
use models\SubParser;

class MainController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('main');
    }

    public function actionResult()
    {
        if (is_null(Core::$session->get('matches'))) {
            $file = $_FILES['file'];
            $subFile = new SubFile($file['name'], $file['type'], $file['size'], $file['tmp_name']);
            try
            {
                $subParser = new SubParser($subFile);
            }
            catch (\Exception $e)
            {
                Core::$session->set('errors', $e->getMessage());
                $this->redirect('/');
            }
            $matches = $subParser->parse();
            Core::$session->set('filename', $file['name']);
        }
        else {
            $matches = Core::$session->get('matches');
        }

        return $this->render('result', ['matches' => $matches]);
    }

    public function actionSave()
    {
        $post_dialogues = $_POST['dialogue'];
        $matches = Core::$session->get('matches');
        $filename = Core::$session->get('filename');
        $dialogues = Core::$session->get('dialogues');
        $content = Core::$session->get('content');
        if (is_null($matches) || is_null($dialogues) || is_null($content) || is_null($filename)) {
            Core::$session->set('errors', 'Что-то пошло не так \\');
            $this->redirect('/');
        }

        foreach ($post_dialogues as $key => $post_dialog) {
            $len = mb_strlen($dialogues[$key]['text']) - $dialogues[$key]['offset'];
            $dialogue = StringHelper::mb_substr_replace($dialogues[$key]['text'], $post_dialog, $dialogues[$key]['offset'], $len);
            $dialogue = preg_replace('/\s\{\\\N\}/', '\N', $dialogue);
            $content[$key] = $dialogue;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        foreach ($content as $value) {
            echo $value;
        }
        exit();

    }
}