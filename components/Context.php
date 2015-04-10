<?php

namespace pahanini\restdoc\components;

use Yii;
use phpDocumentor\Reflection\FileReflector;

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
        $reflection = new FileReflector($fileName, true);
        $reflection->process();

        foreach ($reflection->getClasses() as $reflector) {
            $this->controllers[] = Yii::createObject(
                [
                    'class' => '\pahanini\restdoc\models\ControllerDoc',
                    'reflector' => $reflector
                ]
            );
        }
    }
}
