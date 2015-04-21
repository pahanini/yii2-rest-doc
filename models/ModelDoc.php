<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use Yii;
use yii\base\InvalidConfigException;

class ModelDoc extends ReflectionDoc
{
    public $fields;

    public $scenarios;

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
