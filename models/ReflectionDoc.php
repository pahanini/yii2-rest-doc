<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;

/**
 * Base class for controllers and models.
 */
abstract class ReflectionDoc extends Object
{
    /**
     * Prefix for tags.
     */
    const TAG_PREFIX = 'restdoc-';

    /**
     * @var \phpDocumentor\Reflection\DocBlock
     */
    protected $docBlock;

    /**
     * @var string Keeps last error
     */
    public $error;

    /**
     * @var bool Whether parsed file was valid
     */
    public $isValid;

    /**
     * @var string Long description.
     */
    public $longDescription = '';

    /**
     * @var \Reflector
     */
    public $reflection;

    /**
     * @var string Short description.
     */
    public $shortDescription = '';

    /**
     * @var array Keeps tags.
     */
    private $_tags = [];

    /**
     * Magic tags getter.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_tags)) {
            return $this->_tags[$name];
        }
        parent::__get($name);
    }

    /**
     * Magic tags set check.
     *
     * @param string $name
     * @return mixed
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->_tags) || parent::__isset($name);
    }

    /**
     * Creates object using reflection
     *
     * @return object
     */
    public function getObject()
    {
        return $this->reflection->newInstanceArgs(func_get_args());
    }

    public function getTagsByName($name)
    {
        return isset($this->_tags[$name]) ? $this->_tags[$name] : [];
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

        if (!$this->processDocBlock()) {
            $this->error = $name . ": does not have docBlock";
            $this->isValid = false;
            return;
        }

        if (!$this->processTags($this->docBlock)) {
            $this->error = $name . ": ignore due tag";
            $this->isValid = false;
            return;
        }

        if ($this->reflection->isAbstract()) {
            $this->error = $name . ": isAbstract";
            $this->isValid = false;
            return;
        }

        try {
            $this->process();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->isValid = false;
        }
    }

    abstract public function process();

    /**
     * Parses DocBlock,
     *
     * @return bool
     */
    public function processDocBlock()
    {
        if (!$this->docBlock = new DocBlock($this->reflection)) {
            return false;
        }
        $this->shortDescription = $this->docBlock->getShortDescription();
        $this->longDescription = $this->docBlock->getLongDescription()->getContents();
        return true;
    }

    /**
     * Parses tags.
     *
     * @return bool If tags parsed.
     */
    public function processTags($docBlock)
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
        return !isset($this->ignore);
    }

    /**
     * Registers all tags handlers.
     */
    public static function registerTagHandlers()
    {
        static $isRegistered;

        if (!$isRegistered) {
            $mapping = [
                'query' => '\pahanini\restdoc\models\QueryTag',
                'field' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag'
            ];
            foreach ($mapping as $suffix => $class) {
                $tagName = self::TAG_PREFIX .$suffix;
                Tag::registerTagHandler($tagName, $class);
            }
        }
    }
}
