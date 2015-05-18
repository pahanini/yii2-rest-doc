<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use \Reflector;
use Yii;

class ModelDoc extends ClassDoc
{
    /**
     * @var \pahanini\restdoc\models\FieldDoc[]
     */
    public $fields;

    /**
     * @var string[]
     */
    public $scenarios;

    /**
     * @var array Array of properties indexed by variable name
     */
    private $_propertyTags = [];


    protected function addPropertyTag($tag)
    {
        $name = trim($tag->getVariableName(), '$');
        $this->_propertyTags[$name] = $tag;
    }


    protected function getPropertyTag($name)
    {
        $tags = $this->getPropertyTags();
        return isset($tags[$name]) ? $tags[$name] : null;
    }


    protected function getPropertyTags()
    {
        return $this->_propertyTags;
    }


    public function process($source)
    {
        parent::process($source);

        /** @var \yii\db\ActiveRecord $model */
        /** @var \phpDocumentor\Reflection\DocBlock\Tag\ParamTag $tag */
        $model = $this->createObject();
        $fields = $model->fields();
        $this->scenarios = $model->scenarios();
        $this->fields = [];
        foreach($fields as $key => $value) {
            $this->createField(is_numeric($key) ? $value : $key);
        }

        $this->processFieldsDocBlock($this);

        foreach($this->getTagsByName('field') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $field = isset($this->fields[$name])
                ? $this->fields[$name]
                : $this->createField($name);

            $field->description = $tag->getDescription();
            $field->type = $tag->getType();
        }

        foreach($this->getTagsByName('link') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $field = isset($this->fields[$name])
                ? $this->fields[$name]
                : $this->createField($name);
            $propertyName = $tag->getType() ? trim($tag->getType(), '\\') : $name;
            foreach ($this->scenarios as &$scenario) {
                if (in_array($propertyName, $scenario)) {
                    $scenario[] = $name;
                }
            }
            if ($propertyTag = $this->getPropertyTag($propertyName)) {
                $field->description = $propertyTag->getDescription();
                $field->type = $propertyTag->getType();
            }
        }
    }


    /**
     * Extracts data from reflection's docBlock and adds it to current doc.
     *
     * @param Reflector|\Reflector $reflection
     * @param \pahanini\restdoc\models\ReflectionDoc $doc
     * @return bool $doc If docBlock
     */
    protected function parseDocBlock(Reflector $reflection, $doc)
    {
        $result = parent::parseDocBlock($reflection, $doc);
        if ($docBlock = new DocBlock($reflection)) {
            foreach ($docBlock->getTagsByName('property') as $tag) {
                $doc->addPropertyTag($tag);
            }
        }
        return $result;
    }


    public function processFieldsDocBlock($doc)
    {
        if ($this->parseDocBlock($this->reflection->getMethod('fields'), $doc)) {
            $this->getParentDoc()->processFieldsDocBlock($this);
        }
    }

    /**
     * @param $name
     * @param array $params
     * @return \pahanini\restdoc\models\FieldDoc
     */
    protected function createField($name, $params = [])
    {
        return $this->fields[$name] = Yii::createObject(
            array_merge(
                [
                    'class' => '\pahanini\restdoc\models\FieldDoc',
                    'model' => $this,
                    'name' => $name
                ],
                $params
            )
        );
    }
}
