<?php

namespace tests\models;

use yii\db\ActiveRecord;

/**
 * @inheritdoc
 */
class SpecialOffer extends Product
{
    /**
     * @restdoc-field int $id
     * @restdoc-field string $title
     */
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
