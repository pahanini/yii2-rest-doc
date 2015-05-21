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

        $doc = $context->getControllers()['product'];
        $this->assertEquals('Product Controller.', $doc->shortDescription);
        $this->assertEquals(
            'Product controller allows to manipulate with products. Second line of description.',
            str_replace("\n", " ", $doc->longDescription)
        );
        $this->assertEquals('product', $doc->path);
        $this->assertEquals('name', $doc->query[0]->variableName);
    }

    public function testBrand()
    {
        $context = new Context();
        $context->addFile(Yii::getAlias('@tests/controllers/BrandController.php'));

        $product = $context->getControllers()['brand'];
        $this->assertEquals('Brand Controller.', $product->shortDescription);
        $this->assertEquals('brand', $product->path);
    }

    public function testSort()
    {
        $context = new Context();
        $context->addDirs(Yii::getAlias('@tests/controllers'));
        $context->sortControllers('shortDescription');
        $controllers = $context->getControllers();
        $this->assertEquals(['brand', 'new-brand', 'product'], array_keys($controllers));
    }

    public function testModule()
    {
        $context = new Context();
        $context->addModule('tests\modules\api\Module');

        $controllers = $context->getControllers();
        $this->assertEquals(3, count($controllers));

        $this->assertEquals("Manager's comment", $controllers['product']->model->fields['note']->description);
    }
}
