<?php

namespace controllers;

use core\Core;
use core\base_classes\BaseController;
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
        $file_content = $subParser->getContent();
        return $this->render('result', ['file_content' => $file_content]);
    }
}