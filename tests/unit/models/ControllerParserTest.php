<?php

namespace tests\unit\models;

use pahanini\restdoc\models\ControllerDoc;
use pahanini\restdoc\models\ControllerParser;
use Yii;

class ControllerParserTest extends \PHPUnit_Framework_TestCase
{
    public function testInherit()
    {
        $parser = Yii::createObject(
            [
                'class' => ControllerParser::className(),
                'reflection' => new \ReflectionClass('\tests\controllers\NewBrandController'),
            ]
        );
        $doc = new ControllerDoc();

        $parser->parse($doc);
        $doc->prepare();

        $this->assertEquals('New brand Controller.', $doc->shortDescription);
        $this->assertEquals('Brand info.', $doc->longDescription);
        $this->assertEquals(2, count($doc->query));
    }


    public function testTags()
    {
        $parser = Yii::createObject(
            [
                'class' => ControllerParser::className(),
                'reflection' => new \ReflectionClass('\tests\controllers\ProductController'),
            ]
        );
        $doc = new ControllerDoc();

        $parser->parseClass($doc);
        $doc->prepare();

        $this->assertEquals('name', $doc->query[0]->variableName);
        $this->assertEquals('false', $doc->query[0]->defaultValue);
        $this->assertTrue($doc->hasLabel('labelA'));
        $this->assertFalse($doc->hasLabel('labelB'));
    }
}
