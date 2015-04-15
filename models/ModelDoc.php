<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use Yii;

class ModelDoc extends ReflectionDoc
{
    public function getFields()
    {
        return isset($this->field) ? $this->field : [];
    }

    public function process()
    {
        $fieldsReflection = $this->reflection->getMethod('fields');
        $docBlock = new DocBlock($fieldsReflection);
        $this->processTags($docBlock);
    }
}
