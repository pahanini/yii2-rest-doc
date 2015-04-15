<?php

namespace pahanini\restdoc\models;

use Yii;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\StringHelper;
use yii\rest\ActiveController;

class ModelDoc extends ClassDoc
{
    public $fields = [];

    public function process()
    {
        return true;
    }
}
