<?php

namespace pahanini\restdoc\models;

use Yii;
use yii\helpers\Inflector;

class ControllerDoc extends ClassDoc
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
     * @var array Controller constructor's params
     */
    public $objectArgs = [null, null];

    /**
     * @var string Path to controllers (part of url).
     */
    public $path;

    /**
     * @var array of query tags
     */
    public $query;

    /**
     * @return void
     */
    public function process()
    {
        parent::process();

        // Path
        $this->path = Inflector::camel2id(substr($this->reflection->getShortName(), 0, -strlen('Controller')));

        // Actions
        $this->actions = array_keys($this->getObject()->actions());

        // Model
        $this->model = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelDoc',
                'reflection' => new \ReflectionClass($this->getObject()->modelClass),
            ]
        );

        // Query
        $this->query = $this->getTagsByName('query');
    }
}
