<?php

namespace pahanini\restdoc\controllers;

use Yii;
use yii\console\Controller;

class BuildController extends Controller
{
    public $sourceDir;

    public $targetFile;

    public $template;

    public function actionRun()
    {
        $this->renderPartial(Yii::getAlias($this->template), $this->getParams());
    }

    public function getParams()
    {
        $context = new Context();
        $files = FileHelper::findFiles(Yii::getAlias($this->sourceDir), [
            'only' => ['*Controller.php']
        ]);
        foreach ($files as $file) {
            $context->addFile($file);
        }
        return [
            'controllers' => $context->controllers,
        ];
    }
}
