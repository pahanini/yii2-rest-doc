<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock;
use Yii;

class FieldDoc extends Doc
{
    private $_description;
    private $_type;
    private $_name;
    private $_scenarios = [];

    public function getDescription()
    {
        return $this->_description;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function isInScenario($name)
    {
        return isset($this->_scenarios[$name]);
    }

    public function setDescription($value)
    {
        if ($value) {
            $this->_description = $value;
        }
    }

    public function setName($value)
    {
        if ($value) {
            $this->_name = $value;
        }
    }

    public function setType($value)
    {
        if ($value) {
            $this->_type = $value;
        }
    }

    public function setScenarios(array $value)
    {
        $this->_scenarios = array_merge($this->_scenarios, $value);
    }
}
