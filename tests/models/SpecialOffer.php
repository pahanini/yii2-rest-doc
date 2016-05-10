<?php

namespace tests\models;

/**
 * @inheritdoc
 *
 * @property string $comment Manager's comment <a href="http://example.com">Detail link.</a>
 *
 * @restdoc-link comment $note
 */
class SpecialOffer extends Product
{
    public $comment = 'MyComment';

    /**
     * @inheritdoc
     *
     * @restdoc-field string $title
     * @restdoc-link comment $text
     * @restdoc-link $comment
     * @restdoc-sortField $text
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

    public function scenarios()
    {
        return [
            'api-create' => ['id', 'title', 'note'],
            'api-update' => ['comment'],
        ];
    }
}
