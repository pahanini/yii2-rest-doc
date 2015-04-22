<?php

namespace tests\models;

use yii\db\ActiveRecord;

/**
 * @inheritdoc
 *
 * @property string $comment Manager's comment
 */
class SpecialOffer extends Product
{
    public $comment = 'MyComment';

    /**
     * @restdoc-field int $id
     * @restdoc-field string $title
     * @restdoc-field-use-as comment $text
     */
    public function fields()
    {
        return [
            'id',
            'title' => function () {
                return 'title';
            },
            'text' => 'Comment'
        ];
    }
}
