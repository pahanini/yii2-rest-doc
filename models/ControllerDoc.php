<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\FileReflector;
use Yii;
use yii\base\Object;
use yii\helpers\Inflector;

class ControllerDoc extends Object
{
    /**
     * @var string[] List of controller's actions
     */
    public $actions = [];

    /**
     * @var string Keeps last error
     */
    public $error;

    /**
     * @var string Name of file to parse.
     */
    public $fileName;

    /**
     * @var bool Whether parsed file was valid
     */
    public $isValid;

    /**
     * @var \phpDocumentor\Reflection\ClassReflector
     */
    public $reflector;

    public $longDescription = '';

    public $path;

    public $shortDescription = '';

    /**
     * @var array
     */
    public $tagHandlerMappings = [
        'query' => '\pahanini\restdoc\models\QueryTag',
    ];


    /**
     * @var string Tag prefix
     */
    public $tagPrefix = 'restdoc';

    /**
     * @var array Keeps tags
     */
    private $_tags;

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
     * Class init.
     */
    public function init()
    {
        static::registerTagHandlers($this->tagPrefix, $this->tagHandlerMappings);

        try {
            $this->isValid = $this->process();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->isValid = false;
        }
    }

    /**
     * Registers tags handlers.
     *
     * @param string $prefix
     * @param array $mapping
     */
    public static function registerTagHandlers($prefix, $mapping)
    {
        static $isRegistered;

        if (!$isRegistered) {
            foreach ($mapping as $suffix => $class) {
                $tagName = $prefix . '-' .$suffix;
                Tag::registerTagHandler($tagName, $class);
            }
        }
    }

    /**
     * Parses file.
     *
     * @return bool
     */
    public function process()
    {
        $reflector = new FileReflector($this->fileName);
        $reflector->process();

        $classes = $reflector->getClasses();

        if (count($classes) !== 1) {
            $this->error = "The only class expected to be found in file $this->fileName";
            return false;
        }

        /** @var  $class \phpDocumentor\Reflection\ClassReflector; */
        $class = $classes[0];
        if (!$docBlock = $class->getDocBlock()) {
            $this->error = $this->fileName . " does not have docBlock";
            return false;
        }

        if ($class->isAbstract()) {
            $this->error = $this->fileName . " is abstract";
            return false;
        }

        // parse tags
        $this->_tags = [];
        $tags = $docBlock->getTags();
        $prefix = $this->tagPrefix . '-';
        $offset = strlen($prefix);
        foreach ($tags as $tag) {
            $name = $tag->getName();
            if (strpos($name, $prefix) === 0) {
                $key = substr($name, $offset);
                if (!isset($this->_tags)) {
                    $this->_tags[$key] = [];
                }
                $this->_tags[$key][] = $tag;
            }
        }

        // make sure file is not ignored
        if (isset($this->ignore)) {
            $this->error = $this->fileName . " ignored";
            return false;
        }

        // descriptions
        $this->shortDescription = $docBlock->getShortDescription();
        $this->longDescription = $docBlock->getLongDescription()->getContents();

        // path
        $this->path = Inflector::camel2id(substr($class->getShortName(), 0, -strlen('Controller')));

        // getting actions
        $name = $class->getName();
        $object = new $name(null, null);
        $this->actions = array_keys($object->actions());

        return true;
    }
}
