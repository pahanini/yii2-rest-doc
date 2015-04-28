<?php

namespace tests\modules\api;

class Module extends \yii\base\Module
{
    public $id = 'api';

    public $controllerMap = [
        'brand' => 'tests\controllers\BrandController',
        'product' => [
            'class' => 'tests\controllers\ProductController',
            'modelClass' => 'tests\models\SpecialOffer'
        ],
    ];
}
