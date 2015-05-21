<?php

namespace tests\controllers;

/**
 * New brand Controller.
 *
 * @restdoc-query start-date=- Start date
 *
 * @inheritdoc
 */
class NewBrandController extends BrandController
{
    public $modelClass = 'tests\models\Brand';
}
