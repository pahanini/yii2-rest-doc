<?php

namespace tests\models;

/**
 * @inheritdoc
 *
 * @property string|null $full_name Full name. <a href="http://example.com">Detail link.</a>
 * @restdoc-extraLink $full_name
 */
class NewSpecialOffer extends SpecialOffer
{
    /**
     * @inheritdoc
     *
     * @restdoc-extraField string $alpha2 Code country. <a href="http://example.com">Detail link.</a>
     * @restdoc-ignoreExtraField $ignore
     */
    public function extraFields()
    {
        return [
            'alpha2' => function () {
                return 'RU';
            },
            'full_name' => function () {
                return 'full_name';
            },
            'ignore' => function () {
                return 'ignore';
            }
        ];
    }
}
