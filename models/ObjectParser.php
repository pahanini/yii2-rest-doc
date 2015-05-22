<?php

namespace pahanini\restdoc\models;

use Yii;

/**
 * Parser for yii2 object class and its descendants.
 */
class ObjectParser extends Parser
{
    /**
     * @var mixed
     */
    private $_object;

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
    protected function createObject()
    {
        $object =  $this->reflection->newInstanceArgs($this->objectArgs);
        if ($this->objectConfig) {
            $object = Yii::configure($object, $this->objectConfig);
        }
        return $object;
    }

    /**
     * Returns object based on reflection
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
    public function getParentParser()
    {
        if (!$reflection = $this->reflection->getParentClass()) {
            return false;
        }
        return Yii::createObject(
            [
                'class' => static::className(),
                'reflection' => $reflection,
            ]
        );
    }

    /**
     * Extracts data from reflection's docBlock and adds it to current $doc.
     *
     * @param $doc
     * @return bool $doc If docBlock
     * @throws \Exception
     */
    protected function parseDocBlock($doc)
    {
        if (!$docBlock = new DocBlock($this->reflection)) {
            return false;
        }

        if ($docBlock->getTagsByName(self::TAG_PREFIX . 'ignore')) {
            throw new \Exception("Ignoring due tag");
        }

        if (!$doc->shortDescription && ($value = $docBlock->getShortDescription())) {
            $doc->shortDescription = $value;
        }

        if (!$doc->longDescription && ($value = $docBlock->getLongDescription()->getContents())) {
            $doc->longDescription = $value;
        }

        $tags = $docBlock->getTags();

        $offset = strlen(self::TAG_PREFIX);
        foreach ($tags as $tag) {
            $name = $tag->getName();
            if (strpos($name, self::TAG_PREFIX) === 0) {
                $doc->addTag(substr($name, $offset), $tag);
            }
        }

        return (bool)$docBlock->getTagsByName('inherited') || (bool)$docBlock->getTagsByName('inheritdoc');
    }
}
