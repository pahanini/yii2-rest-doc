<?php

namespace tests\models;

/**
 * @inheritdoc
 * @restdoc-extraField string $full_name Full name.
 */
class NewSpecialOffer extends SpecialOffer
{
    /**
     * @inheritdoc
     *
     * @restdoc-extraField string $alpha2 Code country.
     */
    public function extraFields()
    {
        return [
            'alpha2' => function () {
                return 'RU';
            },
            'full_name' => function () {
                return 'full_name';
            }
        ];
    }
}
