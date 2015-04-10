<?php

namespace pahanini\restdoc\models;

use Yii;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\StringHelper;
use yii\rest\ActiveController;

class ControllerDoc extends Object
{
    public $actions = [];

    /**
     * @var \phpDocumentor\Reflection\ClassReflector
     */
    public $reflector;

    public $longDescription = '';

    public $path;

    public $shortDescription = '';

    public function init()
    {
        // getting long and short description
        if ($reflector = $this->reflector->getDocBlock()) {
            $this->shortDescription = $reflector->getShortDescription();
            $this->longDescription = $reflector->getLongDescription()->getContents();
            if ($tags = $reflector->getTagsByName('restdoc-path')) {
                $this->path = $tags[0]->getContent();
            }
        }

        // getting path if empty
        if (!$this->path) {
            $shortName = $this->reflector->getShortName();
            if (StringHelper::endsWith($shortName, 'Controller')) {
                $this->path = mb_strtolower(substr($shortName, 0, - strlen('Controller')), 'utf-8');
            } else {
                $this->path = '<ENTITY>';
            }
        }

        // getting list of action
        if (!$this->reflector->isAbstract()) {
            $name = $this->reflector->getName();
            try {
                $object = new $name(null, null);
                if ($object instanceof ActiveController) {
                    $this->actions = array_keys($object->actions());
                }
            } catch (Exception $e) {
                Yii::error("Can not create class $name");
            }
        }

        $e = 1;
    }
}
