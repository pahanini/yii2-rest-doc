<?php

namespace pahanini\restdoc\models;

use phpDocumentor\Reflection\DocBlock\Tag;

class QueryTag extends Tag
{
    protected $default = false;

    protected $name = '';

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        if (null === $this->content) {
            $this->content
                = "{$this->type} {$this->variableName} {$this->description}";
        }
        return $this->content;
    }
    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        Tag::setContent($content);

        $parts = preg_split('/\s+/Su', $this->description, 3);


        $parts = preg_split(
            '/(\s+)/Su',
            $this->description,
            3,
            PREG_SPLIT_DELIM_CAPTURE
        );

        // if the first item is always default value
        $this->default = array_shift($parts);

        $name = array_shift($parts);
        if (isset($parts[0])
            && (strlen($parts[0]) > 0)
            && ($parts[0][0] !== '$')
        ) {
            $this->type = array_shift($parts);
            array_shift($parts);
        }

        // if the next item starts with a $ or ...$ it must be the variable name
        if (isset($parts[0])
            && (strlen($parts[0]) > 0)
            && ($parts[0][0] == '$' || substr($parts[0], 0, 4) === '...$')
        ) {
            $this->variableName = array_shift($parts);
            array_shift($parts);

            if (substr($this->variableName, 0, 3) === '...') {
                $this->isVariadic = true;
                $this->variableName = substr($this->variableName, 3);
            }
        }

        $this->setDescription(implode('', $parts));

        $this->content = $content;
        return $this;
    }

    /**
     * Returns the variable's name.
     *
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     * Sets the variable's name.
     *
     * @param string $name The new name for this variable.
     *
     * @return $this
     */
    public function setVariableName($name)
    {
        $this->variableName = $name;

        $this->content = null;
        return $this;
    }

    /**
     * Returns whether this tag is variadic.
     *
     * @return boolean
     */
    public function isVariadic()
    {
        return $this->isVariadic;
    }

}