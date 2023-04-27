<?php

namespace App\Orchid\Layouts\Post;

use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class PostListener extends Listener
{
    /**
     * List of field names for which values will be joined with targets' upon trigger.
     *
     * @var string[]
     */
    protected $extraVars = [
        'post.type',
        'post.parse_type',
    ];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'post.subreddit_id',
        'post.generate'
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
    protected $asyncMethod = 'asyncGenerate';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::block(PostEditLayout::class)
                ->title('Інформація про контент поста')
                ->description('Оновіть інформацію про вміст поста.'),
            Layout::block(PostSecondaryEditLayout::class)
                ->title('Додаткова інформація поста')
                ->description('Оновіть інформацію про другорядні дані.')
        ];
    }
}
