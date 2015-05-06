<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use Yii;
use yii\base\InvalidConfigException;

class ModelDoc extends ReflectionDoc
{
    public $fields;

    public $scenarios;

    /**
     * @var array Array of properties indexed by variable name
     */
    private $_propertyTags;

    protected function getPropertyTag($name)
    {
        $tags = $this->getPropertyTags();
        return isset($tags[$name]) ? $tags[$name] : null;
    }

    protected function getPropertyTags()
    {
        if ($this->_propertyTags === null) {
            $this->_propertyTags = [];
            foreach ($this->docBlock->getTagsByName('property') as $tag) {
                $name = trim($tag->getVariableName(), '$');
                $this->_propertyTags[$name] = $tag;
            }
        }
        return $this->_propertyTags;
    }

    public function process()
    {
        $fieldsReflection = $this->reflection->getMethod('fields');
        $docBlock = new DocBlock($fieldsReflection);
        $this->processTags($docBlock);

        /** @var \yii\db\ActiveRecord $model */
        /** @var \phpDocumentor\Reflection\DocBlock\Tag\ParamTag $tag */
        $model = $this->getObject();
        $fields = $model->fields();
        $this->scenarios = $model->scenarios();

        $this->fields = [];
        foreach($fields as $key => $value) {
            $this->createField(is_numeric($key) ? $value : $key);
        }

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
