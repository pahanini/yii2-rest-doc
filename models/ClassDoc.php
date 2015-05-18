<?php

namespace pahanini\restdoc\models;

use Yii;


/**
 * Keeps Class Doc based of reflection.
 */
class ClassDoc extends ReflectionDoc
{
    /**
     * @var mixed
     */
    private $_object;

    /**
     * @var \pahanini\restdoc\models\ReflectionDoc class, based on context class parent
     */
    private $_parent;

    /**
     * @var
     */
    public $objectArgs = [];

    /**
     * @var
     */
    public $objectConfig;

    /**
     * Creates object using reflection and configures it with $objectConfig array values
     *
     * @return object
     */
    public function createObject()
    {
        $object =  $this->reflection->newInstanceArgs($this->objectArgs);
        if ($this->objectConfig) {
            $object = Yii::configure($object, $this->objectConfig);
        }
        return $object;
    }

    /**
     * @ignore
     */
    public function getObject()
    {
        if (!$this->_object) {
            $this->_object = $this->createObject();
        }
        return $this->_object;
    }

    /**
     * @return bool|object|ReflectionDoc
     * @throws InvalidConfigException
     */
    public function getParentDoc()
    {
        if ($this->_parent === null) {
            if ($reflection = $this->reflection->getParentClass()) {
                $this->_parent = Yii::createObject(
                    [
                        'class' => self::className(),
                        'reflection' => $reflection,
                    ]
                );
            } else {
                $this->_parent = false;
            }
        }
        return $this->_parent;
    }


    public function process()
    {
        $this->processClassDocBlock($this);

        parent::process();
    }


    public function processClassDocBlock($doc)
    {
        if ($this->parseDocBlock($this->reflection, $doc)) {
            $this->getParentDoc()->processClassDocBlock($this);
        }
    }
}
