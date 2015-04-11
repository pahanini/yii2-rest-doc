<?php

namespace pahanini\restdoc\models;

use Yii;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\StringHelper;
use yii\rest\ActiveController;

class ModelDoc extends Object
{
    const LOG_CATEGORY = 'restdoc';

    public $actions = [];

    public $fileName;

    /**
     * @var \phpDocumentor\Reflection\ClassReflector
     */
    public $reflector;



    public $longDescription = '';

    public $path;

    public $shortDescription = '';

    public function init()
    {
        parent::init();
        $this->fileName = realpath($this->fileName);
    }

    public function process()
    {
        if (!$docBlock = $this->reflector->getDocBlock()) {
            Yii::error($this->fileName . " does not have docBlock", self::LOG_CATEGORY);
            return false;
        }
        if ($this->reflector->isAbstract()) {
            Yii::trace($this->fileName . " is abstract", self::LOG_CATEGORY);
            return false;
        }

        // is ignored
        if ($tags = $docBlock->getTagsByName('restdoc-ignore')) {
            Yii::trace($this->fileName . " ignored", self::LOG_CATEGORY);
            return false;
        }

        // descriptions
        $this->shortDescription = $docBlock->getShortDescription();
        $this->longDescription = $docBlock->getLongDescription()->getContents();

        // getting path
        if ($tags = $docBlock->getTagsByName('restdoc-path')) {
            $this->path = $tags[0]->getContent();
        }
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
                Yii::error("Can not create class $name, " . $e->getMessage(), self::LOG_CATEGORY);
                return false;
            }
        }

        return true;
    }
}
