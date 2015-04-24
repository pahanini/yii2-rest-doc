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
 * @restdoc-query name=false Name of part of name to find users
 * @restdoc-query brand= Id of the brand
 * @restdoc-label labelA
 *
 */
class ProductController extends ActiveController
{
    public $modelClass = 'tests\models\Product';
}
