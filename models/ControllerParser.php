<?php

namespace pahanini\restdoc\models;

use pahanini\restdoc\helpers\DocBlockHelper;
use phpDocumentor\Reflection\DocBlock;
use Yii;
use yii\helpers\Inflector;

class ControllerParser extends ObjectParser
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
     * @param \pahanini\restdoc\models\Doc
     * @return void
     */
    public function parse(Doc $doc)
    {
        if ($this->reflection->isAbstract()) {
            $this->error = $this->reflection->name . " is abstract";
            return false;
        }

        $this->parseClass($doc);

        if ($doc->getTagsByName('ignore')) {
            $this->error = $this->reflection->name . " has ignore tag";
            return false;
        }

        $object = $this->getObject();

        $doc->path = Inflector::camel2id(substr($this->reflection->getShortName(), 0, -strlen('Controller')));
        $doc->actions = array_keys($object->actions());

        // Parse model
        $modelParser = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelParser',
                'reflection' => new \ReflectionClass($this->getObject()->modelClass),
            ]
        );
        $doc->model = new ModelDoc();
        $modelParser->parse($doc->model);

        return true;
    }

    /**
     * @param $doc
     * @return bool
     */
    public function parseClass(ControllerDoc $doc)
    {
        if (!$docBlock = new DocBlock($this->reflection)) {
            return false;
        }

        $doc->longDescription = $docBlock->getLongDescription()->getContents();
        $doc->shortDescription = $docBlock->getShortDescription();

        $doc->populateTags($docBlock);

        if (DocBlockHelper::isInherit($docBlock)) {
            $parentParser = $this->getParentParser();
            $parentParser->parseClass($doc);
        }
    }
}
