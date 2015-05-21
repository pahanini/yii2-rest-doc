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
    private $_fields = [];

    private $_parent = null;

    private $_properties = [];

    private $_scenarios = [];

    public function addField($name, $type = '', $description = '', $scenarios = [])
    {
        if (!isset($this->_fields[$name])) {
            $field = new FieldDoc();
            $field->setName($name);
            $field->setParent($this);
            $this->_fields[$name] = $field;
        }
        $this->_fields[$name]->setScenarios($scenarios);
        $this->_fields[$name]->setDescription($description);
        $this->_fields[$name]->setType($type);
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
     *
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

        foreach ($this->_fields as $field) {
            $field->prepare();
        }
    }

    public function setParent(ControllerDoc $value)
    {
        $this->_parent = $value;
    }
}
