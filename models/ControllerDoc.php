<?php

namespace pahanini\restdoc\models;

use yii\base\Object;

class ControllerDoc extends Object
{
    /**
     * @var \phpDocumentor\Reflection\ClassReflector
     */
    public $reflector;

    public $longDescription;

    public $shortDescription;

    public function init()
    {
        if ($reflector = $this->reflector->getDocBlock()) {
            $this->shortDescription = $reflector->getShortDescription();
            $this->longDescription = $reflector->getLongDescription()->getContents();
        }
    }
}
