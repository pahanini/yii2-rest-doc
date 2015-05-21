<?php

namespace pahanini\restdoc\models;

use pahanini\restdoc\helpers\DocBlockHelper;
use phpDocumentor\Reflection\DocBlock;
use Yii;

class ModelParser extends ObjectParser
{
    /**
     * @return void
     */
    public function parse(ModelDoc $doc)
    {
        $object = $this->getObject();

        foreach ($object->scenarios() as $key => $fields) {
            $doc->addScenario($key, $fields);
        }

        foreach ($object->fields() as $key => $value) {
            $doc->addField(is_numeric($key) ? $value : $key);
        }

        $this->parseClass($doc);
        $this->parseFields($doc);

        return true;
    }


    /**
     * @param $doc
     * @return bool
     */
    public function parseClass(ModelDoc $doc)
    {
        if (!$docBlock = new DocBlock($this->reflection)) {
            return false;
        }

        $doc->populateProperties($docBlock);
        $doc->populateTags($docBlock);

        if (DocBlockHelper::isInherit($docBlock)) {
            $parentParser = $this->getParentParser();
            $parentParser->parseClass($doc);
        }
    }

    /**
     * @param $doc
     * @return bool
     */
    public function parseFields(ModelDoc $doc)
    {
        if (!$docBlock = new DocBlock($this->reflection->getMethod('fields'))) {
            return false;
        }

        $doc->populateTags($docBlock);

        if (DocBlockHelper::isInherit($docBlock)) {
            $parentParser = $this->getParentParser();
            $parentParser->parseFields($doc);
        }
    }
}
