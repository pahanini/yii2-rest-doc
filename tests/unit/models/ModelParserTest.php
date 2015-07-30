<?php

namespace tests\unit\models;

use pahanini\restdoc\models\ModelDoc;
use pahanini\restdoc\models\ModelParser;
use Yii;

class ModelParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $parser = Yii::createObject(
            [
                'class' => ModelParser::className(),
                'reflection' => new \ReflectionClass('\tests\models\SpecialOffer'),
            ]
        );
        $doc = new ModelDoc();

        $parser->parse($doc);
        $doc->prepare();

        $this->assertTrue($doc->hasFields());
        $this->assertFalse($doc->hasExtraFields());
    }

    public function testInherit()
    {
        $parser = Yii::createObject(
            [
                'class' => ModelParser::className(),
                'reflection' => new \ReflectionClass('\tests\models\NewSpecialOffer'),
            ]
        );
        $doc = new ModelDoc();

        $parser->parse($doc);
        $doc->prepare();

        $this->assertEquals(2, count($doc->scenarios));

        $this->assertEquals(2, count($doc->extraFields));
        $this->assertEquals('string', $doc->extraFields['alpha2']->type);
        $this->assertEquals('Code country.', $doc->extraFields['alpha2']->description);
        $this->assertEquals('string', $doc->extraFields['full_name']->type);
        $this->assertEquals('Full name.', $doc->extraFields['full_name']->description);

        $this->assertEquals(5, count($doc->fields));
        $this->assertEquals('int', $doc->fields['id']->type);
        $this->assertEquals('string', $doc->fields['title']->type);
        $this->assertEquals('string', $doc->fields['comment']->type);
        $this->assertEquals('string', $doc->fields['note']->type);
        $this->assertEquals('string', $doc->fields['text']->type);

        $this->assertTrue($doc->fields['id']->isInScenario('api-create'));
        $this->assertFalse($doc->fields['id']->isInScenario('api-update'));

        $this->assertTrue($doc->fields['text']->isInScenario('api-update'));
        $this->assertFalse($doc->fields['text']->isInScenario('api-create'));

        $this->assertTrue($doc->hasFields());
        $this->assertTrue($doc->hasExtraFields());
    }

}
