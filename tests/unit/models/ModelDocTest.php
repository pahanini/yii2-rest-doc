<?php

namespace tests\unit\models;

use Yii;

class ModelDocTest extends \PHPUnit_Framework_TestCase
{
    public function testTags()
    {
        $reflection = new \ReflectionClass('tests\models\Product');
        $doc = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelDoc',
                'reflection' => $reflection,
            ]
        );
        $this->assertTrue($doc->isValid, $doc->error);
        $this->assertEquals(2, count($doc->getTagsByName('field')));
    }
}
