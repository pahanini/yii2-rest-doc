<?php

namespace tests\controllers;

use yii\rest\ActiveController;

/**
 * Brand Controller.
 *
 * Brand info.
 *
 * @restdoc-query finish-date=- Finish date
 */
class BrandController extends ActiveController
{
    public $modelClass = 'tests\models\Brand';
}
