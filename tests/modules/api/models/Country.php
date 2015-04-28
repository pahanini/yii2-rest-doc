<?php

namespace tests\modules\api\models;

use yii\db\ActiveRecord;

/**
 * Class Country
 *
 * Represents product's origin country.
 *
 * @restdoc-field string $title Title description.
 *
 */
class Country extends ActiveRecord
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
