<?php

namespace pahanini\restdoc\models;

use yii\base\Object;
use phpDocumentor\Reflection\DocBlock;

/**
 * Base document.
 */
class Doc extends  Object
{
    const TAG_PREFIX = 'restdoc-';

    /**
     * @var \pahanini\restdoc\models\Doc
     */
    private $_parent;

    /**
     * @var
     */
    private $_tags;


    /**
     * @return \pahanini\restdoc\models\Doc
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Returns tags with given name.
     *
     * @param $name
     * @return array
     */
    public function getTagsByName($name)
    {
        return isset($this->_tags[$name]) ? $this->_tags[$name] : [];
    }

    /**
     * @param Doc $value
     */
    public function setParent(Doc $value)
    {
        $this->_parent = $value;
    }

    /**
     * Extracts tags from docBlock and adds it to document.
     *
     * @param DocBlock $docBlock
     */
    public function populateTags(DocBlock $docBlock)
    {
        $tags = $docBlock->getTags();
        $offset = strlen(self::TAG_PREFIX);
        foreach ($tags as $tag) {
            $name = $tag->getName();
            if (strpos($name, self::TAG_PREFIX) === 0) {
                $key = substr($name, $offset);
                if (!isset($this->_tags)) {
                    $this->_tags[$key] = [];
                }
                $this->_tags[$key][] = $tag;
            }
        }
    }

    /**
     * Prepares doc.
     */
    public function prepare()
    {
        null;
    }
}
