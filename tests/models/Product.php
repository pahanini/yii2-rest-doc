<?php

namespace tests\models;

use yii\db\ActiveRecord;

/**
 * Product
 *
 * Product description. <a href="http://example.com">Details link.</a>
 */
class Product extends ActiveRecord
{
    /**
     * @restdoc-field int $id
     * @restdoc-field string $title
     * @restdoc-sortField $title
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
