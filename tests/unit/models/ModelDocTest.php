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

        $reflection = new \ReflectionClass('tests\models\Brand');
        $doc = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelDoc',
                'reflection' => $reflection,
            ]
        );
        $this->assertTrue($doc->isValid, $doc->error);
        $this->assertEquals(2, count($doc->getTagsByName('field')));
        $this->assertEquals(2, count($doc->fields));
        $this->assertEquals("Title description.", $doc->fields['title']->description);
        $this->assertEquals("string", $doc->fields['title']->type);
        $this->assertEquals("int", $doc->fields['id']->type);
        $this->assertTrue($doc->fields['title']->isInScenario('api'));
        $this->assertFalse($doc->fields['id']->isInScenario('api'));
    }


}
