<?php

namespace App\Orchid\Layouts\Post;

use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;

class PostMassiveGenerateListener extends Listener
{
    /**
     * List of field names for which values will be joined with targets' upon trigger.
     *
     * @var string[]
     */
    protected $extraVars = [
        'post.type',
        'post.parse_type',
        'post.bots.',
        'post.can_insert'
    ];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'post.subreddit_id'
    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncMassiveGenerate';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            PostMassiveGenerateLayout::class
        ];
    }
}
