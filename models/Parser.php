<?php

namespace pahanini\restdoc\models;

use yii\base\Object;
use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;

class Parser extends  Object
{
    const TAG_PREFIX = 'restdoc-';

    /**
     * @var string Keeps last error
     */
    public $error;

    /**
     * @var ReflectionClass
     */
    public $reflection;

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
    }

    /**
     * @param $doc
     * @return bool Weather parse was successful
     */
    public function parse($doc)
    {
        return false;
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
