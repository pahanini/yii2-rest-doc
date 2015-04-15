<?php

namespace tests\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public function fields()
    {
        return [
            'id',
            'title' => function () {
                return 'title';
            }
        ];
    }
}
