<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use Yii;

/**
 * Class ModelDoc
 *
 * @property \pahanini\restdoc\models\FieldDoc[] $fields
 */
class ModelDoc extends Doc
{
    private $_extraFields = [];

    private $_fields = [];

    private $_parent = null;

    private $_properties = [];

    private $_scenarios = [];

    /**
     * @param mixed $fields
     * @param string $name
     * @param string $type
     * @param string $description
     * @param array $scenarios
     */
    private function _addField(&$fields, $name, $type = '', $description = '', $scenarios = [])
    {
        if (!isset($fields[$name])) {
            $field = new FieldDoc();
            $field->setName($name);
            $field->setParent($this);
            $fields[$name] = $field;
        }
        $fields[$name]->setScenarios($scenarios);
        $fields[$name]->setDescription($description);
        $fields[$name]->setType($type);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $description
     * @param array $scenarios
     */
    public function addField($name, $type = '', $description = '', $scenarios = [])
    {
        $this->_addField($this->_fields, $name, $type, $description, $scenarios);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $description
     */
    public function addExtraField($name, $type = '', $description = '')
    {
        $this->_addField($this->_extraFields, $name, $type, $description);
    }

    /**
     * Adds scenario info
     *
     * @param $key
     * @param array $fields
     */
    public function addScenario($key, array $fields)
    {
        $this->_scenarios[$key] = $fields;
    }

    /**
     * @return \pahanini\restdoc\models\FieldDoc[]
     */
    public function getExtraFields()
    {
        return $this->_extraFields;
    }

    /**
     * @return \pahanini\restdoc\models\FieldDoc[]
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @return \pahanini\restdoc\models\ControllerDoc
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Returns property tag
     *
     * @param $name
     * @return mixed
     */
    protected function getProperty($name)
    {
        return isset($this->_properties[$name]) ? $this->_properties[$name] : null;
    }

    /**
     * Returns scenario
     *
     * @return array
     */
    public function getScenario($name)
    {
        return isset($this->_scenarios[$name]) ? $this->_scenarios[$name] : null;
    }

    /**
     * Returns scenarios
     *
     * @return array
     */
    public function getScenarios()
    {
        return $this->_scenarios;
    }

    /**
     * Returns array scenarios having given variable name
     *
     * @return array
     */
    public function getScenariosHaving($name)
    {
        $result = [];
        foreach ($this->getScenarios() as $key => $values) {
            if (in_array($name, $values)) {
                $result[$key] = $values;
            }
        }
        return $result;
     }

    /**
     * @return bool If model has extra fields
     */
    public function hasExtraFields()
    {
        return !empty($this->_extraFields);
    }

    /**
     * @return bool If model has fields
     */
    public function hasFields()
    {
        return !empty($this->_fields);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasScenario($name)
    {
        return isset($this->_scenarios[$name]);
    }

    /**
     * @param DocBlock $docBlock
     */
    public function populateProperties(DocBlock $docBlock)
    {
        foreach ($docBlock->getTagsByName('property') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $this->_properties[$name] = $tag;
        }
    }

    /**
     * @todo refactor this code, may be it is a good idea to join fields and extra fields in one array
     */
    public function prepare()
    {
        foreach ($this->getFields() as $field) {
            $field->setScenarios($this->getScenariosHaving($field->getName()));
        }

        foreach($this->getTagsByName('field') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $this->addField($name, $tag->getType(), $tag->getDescription(), $this->getScenariosHaving($name));
        }

        foreach ($this->getTagsByName('extraField') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $this->addExtraField($name, $tag->getType(), $tag->getDescription());
        }

        foreach($this->getTagsByName('link') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $propertyName = $tag->getType() ? trim($tag->getType(), '\\') : $name;
            if ($propertyTag = $this->getProperty($propertyName)) {
                $this->addField(
                    $name,
                    $propertyTag->getType(),
                    $propertyTag->getDescription(),
                    array_merge(
                        $this->getScenariosHaving($name),
                        $this->getScenariosHaving($propertyName)
                    )
                );
            }
        }

        foreach($this->getTagsByName('extraLink') as $tag) {
            $name = trim($tag->getVariableName(), '$');
            $propertyName = $tag->getType() ? trim($tag->getType(), '\\') : $name;
            if ($propertyTag = $this->getProperty($propertyName)) {
                $this->addExtraField(
                    $name,
                    $propertyTag->getType(),
                    $propertyTag->getDescription(),
                    array_merge(
                        $this->getScenariosHaving($name),
                        $this->getScenariosHaving($propertyName)
                    )
                );
            }
        }

        foreach ($this->_fields as $field) {
            $field->prepare();
        }
        foreach ($this->_extraFields as $field) {
            $field->prepare();
        }
    }

    public function setParent(Doc $value)
    {
        $this->_parent = $value;
    }
}
