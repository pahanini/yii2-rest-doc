<?php

namespace pahanini\restdoc\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;
use pahanini\restdoc\components\Context;

/**
 * Builds REST documentation.
 */
class BuildController extends Controller
{
    public $defaultAction = 'run';

    public $sourceDir;

    public $targetFile;

    public $template;

    public function actionRun()
    {
        file_put_contents($this->targetFile, $this->renderPartial(Yii::getAlias($this->template), $this->getParams()));
    }

    public function getParams()
    {
        $context = new Context();
        $dirs = is_array($this->sourceDir) ? $this->sourceDir : [$this->sourceDir];
        foreach ($dirs as $dir) {
            $files = FileHelper::findFiles(Yii::getAlias($dir), [
                'only' => ['*Controller.php']
            ]);
            foreach ($files as $file) {
                $context->addFile($file);
            }
        }

        return [
            'controllers' => $context->controllers,
        ];
    }
}
