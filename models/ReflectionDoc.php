<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tag;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;

/**
 * Base class for controllers and models.
 */
class ReflectionDoc extends Object
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
     * @var \ReflectionClass
     */
    public $reflection;

    /**
     * @var array
     */
    private $_descriptions = [
        'shortDescription' => false,
        'longDescription' => false,
    ];

    /**
     * @var \pahanini\restdoc\ReflectionDoc parent class
     */
    private $_parent;

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

        if (isset($this->_descriptions[$name])) {

            if ($this->_descriptions[$name] === false) {

                $method = 'get' . ucfirst($name);
                $value = $this->docBlock->$method();

                if ($value instanceof Description) {
                    $value = $value->getContents();
                }
                if (!$value && $this->docBlock->getTagsByName('inheritdoc') && ($parent = $this->getParent())) {
                    $value = $parent->$name;
                }

                $this->_descriptions[$name] = $value;
            }

            return $this->_descriptions[$name];
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
        return array_key_exists($name, $this->_tags) || isset($this->_descriptions[$name]) ||parent::__isset($name);
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


    public function getParent()
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

    public function process()
    {
        return null;
    }

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
                'field' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag',
                'field-use-as' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag'
            ];
            foreach ($mapping as $suffix => $class) {
                $tagName = self::TAG_PREFIX .$suffix;
                Tag::registerTagHandler($tagName, $class);
            }
        }
    }
}
