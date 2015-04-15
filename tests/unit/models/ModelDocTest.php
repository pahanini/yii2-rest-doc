<?php

namespace tests\unit\models;

use Yii;

class ModelDocTest extends \PHPUnit_Framework_TestCase
{
    public function testTags()
    {
        $doc = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelDoc',
                'className' => 'tests\models\Product',
            ]
        );
        $this->assertTrue($doc->isValid, $doc->error);
    }
}
