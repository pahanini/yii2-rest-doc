<?php

namespace pahanini\restdoc\components;

use Yii;
use pahanini\restdoc\models\ControllerDoc;

class Context extends \yii\base\Component
{
    /**
     * @var \pahanini\restdoc\models\ControllerDoc[] Keeps controllers.
     */
    public $controllers = [];

    /**
     * Adds file to context.
     *
     * @param string $fileName
     */
    public function addFile($fileName)
    {
        $controller = Yii::createObject(
            [
                'class' => ControllerDoc::className(),
                'fileName' => realpath($fileName),
            ]
        );
        if ($controller->isValid) {
            $this->controllers[$controller->path] = $controller;
        } else {
            Yii::error($controller->error, 'restdoc');
        }

        uasort($this->controllers, function ($a, $b) {
            return strcmp($a->shortDescription, $b->shortDescription);
        });
    }
}
