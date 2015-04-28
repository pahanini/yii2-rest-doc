<?php

namespace tests\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Product Controller of api module.
 *
 * @restdoc-query name=false Name of part of name to find users
 * @restdoc-query brand= Id of the brand
 * @restdoc-label labelA
 *
 */
class CountryController extends ActiveController
{
    public $modelClass = 'tests\modules\api\models\Country';
}
