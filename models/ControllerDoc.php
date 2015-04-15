<?php

namespace pahanini\restdoc\models;

use Yii;
use yii\helpers\Inflector;

class ControllerDoc extends BaseDoc
{
    /**
     * @var string[] List of controller's actions.
     */
    public $actions = [];

    /**
     * @var \pahanini\restdoc\models\ModelDoc
     */
    public $model;

    /**
     * @var string Path to controllers (part of url).
     */
    public $path;

    /**
     * Parses file.
     *
     * @return bool
     */
    public function process()
    {
        // path
        $this->path = Inflector::camel2id(substr($this->reflectionClass->getShortName(), 0, -strlen('Controller')));

        // process action
        $object = $this->getObject(null, null);
        $this->actions = array_keys($object->actions());

        return true;
    }
}
