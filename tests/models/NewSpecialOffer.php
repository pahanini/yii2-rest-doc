<?php

namespace tests\models;

/**
 * @inheritdoc
 *
 * @property string $full_name Full name. <a href="http://example.com">Detail link.</a>
 * @restdoc-extraLink $full_name
 */
class NewSpecialOffer extends SpecialOffer
{
    /**
     * @inheritdoc
     *
     * @restdoc-extraField string $alpha2 Code country. <a href="http://example.com">Detail link.</a>
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
