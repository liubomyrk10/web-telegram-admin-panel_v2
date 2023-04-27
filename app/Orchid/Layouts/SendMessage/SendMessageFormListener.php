<?php

namespace App\Orchid\Layouts\SendMessage;

use App\Models\Bot;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class SendMessageFormListener extends Listener
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
        'send_message.type'
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
    protected $asyncMethod = 'asyncFormBuilder';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([
                    RadioButtons::make('send_message.type')
                        ->options([
                            'text' => 'Текст',
                            'image' => 'Картинка',
                            'gif' => 'Анімація',
                            'video' => 'Відео',
                            'album' => 'Альбом',
                        ])
                        ->title('Тип Reddit постів')
                        ->required()
                        ->value('text'),

                    Relation::make('send_message.bot')
                        ->fromModel(Bot::class, 'username')
                        ->applyScope('onlyOwner')
                        ->required()
                        ->title('Бот')
                        ->help('Вкажіть, підписникам якого боту надсилати повідомлення?'),

                    SimpleMDE::make('send_message.body')
                        ->title('Текст')
                        ->required()
                        ->help('Текст повідомлення. Якщо картинка вказана, тоді розмір тексту до 1024, без - 4096.'),

                    Picture::make('send_message.image')
                        ->title('Картинка')
                        ->acceptedFiles('.jpg, .jpeg, .png, .gif, .webp')
                        ->help('До 5 MB. Максимальна роздільна здатність - 4096х4096 пікселів')
                        ->canSee($this->query->has('image')),

                    Picture::make('send_message.gif')
                        ->title('Анімація')
                        ->acceptedFiles('.gif, .webp')
                        ->help('До 5 MB. Максимальна роздільна здатність - 4096х4096 пікселів')
                        ->canSee($this->query->has('gif')),

                    Upload::make('send_message.video')
                        ->acceptedFiles('.mp4, .avi')
                        ->maxFiles(1)
                        ->canSee($this->query->has('video')),
                ]
            ),
        ];
    }
}
