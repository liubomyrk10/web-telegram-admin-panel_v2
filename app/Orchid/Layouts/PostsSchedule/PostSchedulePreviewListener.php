<?php

namespace App\Orchid\Layouts\PostsSchedule;

use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class PostSchedulePreviewListener extends Listener
{
    /**
     * List of field names for which values will be joined with targets' upon trigger.
     *
     * @var string[]
     */
    protected $extraVars = [];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'post_schedule.post_id'
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
    protected $asyncMethod = 'asyncPostPreview';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::block(PostScheduleEditLayout::class)
                ->title('Інформація про відкладений пост.')
                ->description('Оновіть інформацію про канал, куда постити контент, який саме пост та коли це зробити.'),
            Layout::block(PostPreviewLayout::class)
                ->title('Preview поста'),
        ];
    }
}
