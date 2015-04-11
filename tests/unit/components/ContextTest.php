<?php

namespace tests\unit\components;

use pahanini\restdoc\components\Context;
use Yii;

class ComponentTest extends \PHPUnit_Framework_TestCase
{
    public function testProduct()
    {
        $context = new Context();
        $context->addFile(Yii::getAlias('@tests/controllers/ProductController.php'));

        $doc = reset($context->controllers);
        $this->assertEquals('Product Controller.', $doc->shortDescription);
        $this->assertEquals(
            'Product controller allows to manipulate with products. Second line of description.',
            str_replace("\n", " ", $doc->longDescription)
        );
        $this->assertEquals('product', $doc->path);
        $this->assertEquals('', $doc->query[0]);
    }

    public function testBrand()
    {
        $context = new Context();
        $context->addFile(Yii::getAlias('@tests/controllers/BrandController.php'));

        $doc = reset($context->controllers);
        $this->assertEquals('Brand Controller.', $doc->shortDescription);
        $this->assertEquals('brand', $doc->path);
    }
}
