<?php

namespace pahanini\restdoc\helpers;

use phpDocumentor\Reflection\DocBlock;

class DocBlockHelper
{
    public static function isInherit(DocBlock $docBlock)
    {
        return $docBlock->getTagsByName('inheritdoc') || $docBlock->getTagsByName('inherited');
    }
}
