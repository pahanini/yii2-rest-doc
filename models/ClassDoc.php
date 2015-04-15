<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;
use Yii;
use yii\base\Object;

/**
 * Base class for controllers and models.
 */
abstract class ClassDoc extends Object
{
    /**
     * Prefix for tags.
     */
    const TAG_PREFIX = 'restdoc-';

    /**
     * @var string Keeps name of the class.
     */
    public $className;

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
     * @var \ReflectionClass
     */
    public $reflectionClass;

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
     * Creates object using className.
     *
     * @return object
     */
    public function getObject()
    {
        return $this->reflectionClass->newInstanceArgs(func_get_args());
    }

    /**
     * Class init.
     */
    public function init()
    {
        static::registerTagHandlers();

        $this->reflectionClass = new \ReflectionClass($this->className);

        $this->isValid = true;

        if (!$this->processDocBlock()) {
            $this->error = $this->className . " does not have docBlock";
            $this->isValid = false;
            return;
        }

        if (!$this->processTags()) {
            $this->error = $this->className . ": ignore due tag";
            $this->isValid = false;
            return;

        }

        if ($this->reflectionClass->isAbstract()) {
            $this->error = $this->className . " isAbstract";
            $this->isValid = false;
            return;
        }

        try {
            $this->isValid = $this->process();
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
        if (!$this->docBlock = new DocBlock($this->reflectionClass)) {
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
    public function processTags()
    {
        $tags = $this->docBlock->getTags();
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
            ];
            foreach ($mapping as $suffix => $class) {
                $tagName = self::TAG_PREFIX .$suffix;
                Tag::registerTagHandler($tagName, $class);
            }
        }
    }
}
