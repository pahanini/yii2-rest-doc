<?php

namespace tests\unit\models;

use Yii;

class ModelDocTest extends \PHPUnit_Framework_TestCase
{
    public function testTagsAndDescription()
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
        $this->assertEquals('Product', $doc->shortDescription);
        $this->assertEquals('Product description.', $doc->longDescription);
        $this->assertEquals(2, count($doc->fields));

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

    public function testFieldUseAndScenarios()
    {
        $reflection = new \ReflectionClass('tests\models\SpecialOffer');
        $doc = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelDoc',
                'reflection' => $reflection,
            ]
        );
        $this->assertEquals(5, count($doc->fields));
        $this->assertEquals(3, count($doc->getTagsByName('link')));
        $this->assertEquals("Manager's comment", $doc->fields['text']->description);
        $this->assertEquals("Manager's comment", $doc->fields['note']->description);
        $this->assertEquals("Manager's comment", $doc->fields['comment']->description);
        $this->assertTrue($doc->fields['note']->isInScenario('api-create'));
        $this->assertTrue($doc->fields['note']->isInScenario('api-update'));
        $this->assertFalse($doc->fields['comment']->isInScenario('api-create'));
        $this->assertTrue($doc->fields['comment']->isInScenario('api-update'));
        $this->assertEquals('string', $doc->fields['comment']->type);
    }

    public function testInheritdoc()
    {
        $reflection = new \ReflectionClass('tests\models\SpecialOffer');
        $doc = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ModelDoc',
                'reflection' => $reflection,
            ]
        );
        $this->assertEquals('Product', $doc->shortDescription);
        $this->assertEquals('Product description.', $doc->longDescription);
        $this->assertEquals("int", $doc->fields['id']->type);
    }
}
