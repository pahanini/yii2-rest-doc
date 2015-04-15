<?php

namespace pahanini\restdoc\components;

use Yii;
use pahanini\restdoc\models\ControllerDoc;
use yii\base\InvalidParamException;

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
        $reflector = new FileReflector(fileName);
        $reflector->process();

        $classes = $reflector->getClasses();

        if (count($classes) !== 1) {
            throw new InvalidParamException("File $fileName includes more then one class");
        }

        $controller = Yii::createObject(
            [
                'class' => ControllerDoc::className(),
                'className' => $classes[0]->getName(),
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
