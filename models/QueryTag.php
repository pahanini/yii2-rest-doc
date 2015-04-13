<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock\Tag;

class QueryTag extends Tag
{
    public $defaultValue = false;

    public $variableName = '';

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        Tag::setContent($content);

        $parts = preg_split('/\s+/Su', $this->description, 2);
        $tmp = explode('=', array_shift($parts));
        if (count($tmp) == 2) {
            $this->defaultValue = $tmp[1];
            $this->variableName = $tmp[0];
        } else {
            array_unshift($parts, $tmp);
        }
        $this->setDescription(join(' ', str_replace("\n", " ", $parts)));

        return $this;
    }
}