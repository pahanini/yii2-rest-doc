<?php

namespace pahanini\restdoc\models;

use yii\base\Object;
use phpDocumentor\Reflection\ClassReflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Basic parser.
 */
class Parser extends  Object
{
    /**
     * @var string Keeps last error.
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
     * @param \pahanini\restdoc\models\Doc $doc
     * @return bool Weather parse was successful
     */
    public function parse(Doc $doc)
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
                'extraField' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag',
                'extraLink' => '\phpDocumentor\Reflection\DocBlock\Tag\ParamTag',
            ];
            foreach ($mapping as $suffix => $class) {
                $tagName = Doc::TAG_PREFIX .$suffix;
                Tag::registerTagHandler($tagName, $class);
            }
        }
    }
}
