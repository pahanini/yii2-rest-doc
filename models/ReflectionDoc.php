<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;
use Reflector;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;

/**
 * Keeps doc based of reflection.
 */
class ReflectionDoc extends Object
{
    /**
     * Prefix for tags.
     */
    const TAG_PREFIX = 'restdoc-';

    /**
     * @var string Keeps last error
     */
    public $error;

    /**
     * @var bool Whether parsed file was valid
     */
    public $isValid;

    /**
     * @var string Long description
     */
    public $longDescription;

    /**
     * @var ReflectionClass
     */
    public $reflection;

    /**
     * @var string Short description
     */
    public $shortDescription;

    /**
     * @var array Keeps attached labels.
     */
    private $_labels;

    /**
     * @var array Keeps tags.
     * @see getTagsByName()
     */
    private $_tags = [];

    /**
     * @param $key
     * @param $value
     */
    public function addTag($key, $value)
    {
        if (!isset($this->_tags)) {
            $this->_tags[$key] = [];
        }
        $this->_tags[$key][] = $value;
    }

    /**
     * Returns tags with given name
     *
     * @param $name
     * @return array
     */
    public function getTagsByName($name)
    {
        return isset($this->_tags[$name]) ? $this->_tags[$name] : [];
    }

    /**
     * @param $value
     * @return bool If label attached to doc
     */
    public function hasLabel($value)
    {
        return isset($this->_labels[$value]);
    }

    /**
     * Class init.
     */
    public function init()
    {
        parent::init();
        static::registerTagHandlers();

        if (!($this->reflection instanceof \Reflector)) {
            throw new InvalidConfigException("Reflection property must be set");
        }

        $this->isValid = true;

        $name = $this->reflection->getName();

        if ($this->reflection->isAbstract()) {
            $this->error = $name . ": isAbstract";
            $this->isValid = false;
            return;
        }

        try {
            $this->process($this);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->isValid = false;
            return;
        }
    }

    /**
     * Extracts data from reflection's docBlock and adds it to current $doc.
     *
     * @param \Reflector $reflection
     * @param $doc
     * @return bool $doc If docBlock
     * @throws \Exception
     */
    protected function parseDocBlock(Reflector $reflection, $doc)
    {
        if (!$docBlock = new DocBlock($reflection)) {
            return false;
        }

        if ($docBlock->getTagsByName('ignore')) {
            throw new \Exception("Ignoring");
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


    /**
     ** @return null
     */
    public function process()
    {
        foreach ($this->getTagsByName('label') as $tag) {
            $this->_labels[$tag->getContent()] = true;
        }
    }

    /**
     * Registers all tags handlers.
     */
    public static function registerTagHandlers()
    {
        static $isRegistered;

        if (!$isRegistered) {
            $mapping = [
                'query' => '\pahanini\restdoc\tags\QueryTag',
                'field' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag',
                'field-use-as' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag',
                'link' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag',
                'label' => '\phpDocumentor\Reflection\DocBlock\Tag',
            ];
            foreach ($mapping as $suffix => $class) {
                $tagName = self::TAG_PREFIX .$suffix;
                Tag::registerTagHandler($tagName, $class);
            }
        }
    }
}
