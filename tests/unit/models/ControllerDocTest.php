<?php

namespace tests\unit\models;

use Yii;

class ControllerDocTest extends \PHPUnit_Framework_TestCase
{
    public function testTags()
    {
        $doc = Yii::createObject(
            [
                'class' => '\pahanini\restdoc\models\ControllerDoc',
                'fileName' => Yii::getAlias('@tests/controllers/ProductController.php')
            ]
        );
        $this->assertTrue($doc->isValid, $doc->error);
        $this->assertEquals('name', $doc->query[0]->variableName);
        $this->assertEquals('false', $doc->query[0]->defaultValue);
    }
}
