<?php

namespace tests\controllers;

use yii\rest\ActiveController;

/**
 * Product Controller.
 *
 * Product controller allows to manipulate with products.
 * Second line of description.
 *
 * @link http://example.com
 *
 * @restdoc-query false $name Name of part of name to find users
 *
 */
class ProductController extends ActiveController
{
    public $modelClass = 'tests\models\Product';
}
