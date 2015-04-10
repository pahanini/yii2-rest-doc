<?php

namespace tests\unit;

use pahanini\restdoc\components\Context;
use Yii;

class ComponentTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $context = new Context();
        $context->addFile(Yii::getAlias('@tests/controllers/ProductController.php'));

        $doc = $context->controllers[0];
        $this->assertEquals('Product Controller.', $doc->shortDescription);
        $this->assertEquals(
            'Product controller allows to manipulate with products. Second line of description.',
            str_replace("\n", " ", $doc->longDescription)
        );
    }
}
