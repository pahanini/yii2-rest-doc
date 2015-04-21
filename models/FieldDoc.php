<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use Yii;
use yii\base\Object;

class FieldDoc extends Object
{
    public $description;

    public $model;

    public $name;

    public $type;

    public function isInScenario($name)
    {
        $scenarios = $this->model->scenarios;
        $result =  isset($scenarios[$name]) && (array_search($this->name, $scenarios[$name]) !== false);
        return $result;
    }
}
