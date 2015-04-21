<?php

namespace tests\models;

use yii\db\ActiveRecord;

/**
 * Class Brand
 *
 * @restdoc-field string $title Title description.
 *
 */
class Brand extends ActiveRecord
{
    /**
     * @restdoc-field int $id
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

    public function scenarios()
    {
        return [
            'api' => ['title']
        ];
    }
}
