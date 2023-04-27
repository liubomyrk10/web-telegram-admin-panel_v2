<?php

namespace App\Orchid\Layouts\Post;

use App\Models\Bot;
use App\Models\Subreddit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class PostEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    protected function fields(): iterable
    {
        return [
            Input::make('post.id')->type('hidden'),

            RadioButtons::make('post.parse_type')
                ->options([
                    'new' => 'New',
                    'top' => 'Top',
                    'hot' => 'Hot',
                    'controversial' => 'Controversial',
                ])
                ->title('Тип Reddit постів')
                ->required(),

            RadioButtons::make('post.type')
                ->options([
                    'image' => 'Фото',
                    'video' => 'Відео',
                    'youtube' => 'Youtube',
                    'gif' => 'Гіф',
                    'text' => 'Текст',
                ])
                ->title('Тип посту')
                ->required(),

            Group::make([
                Relation::make('post.subreddit_id')
                    ->fromModel(Subreddit::class, 'name')
                    ->required()
                    ->title('Субреддіти')
                    ->help('Після вибору субреддіта, буде спроба автозаповнення полів.'),
                CheckBox::make('post.generate')
                    ->title('Заповнити')
                    ->help('Якщо потрібно автозаповнити обраний субреддіт.'),
            ]),


            Label::make('post.generate_status')
                ->value('Автозаповнення ще не виконувалось.')
                ->class('alert alert-info'),

            Relation::make('post.bots.')
                ->fromModel(Bot::class, 'username')
                ->applyScope('onlyOwner')
                ->multiple()
                ->required()
                ->title('Бот')
                ->help('Вкажіть, які боти буде мати доступ до поста.'),

            Input::make('post.reddit_id')
                ->type('text')
                ->title('Reddit id')
                ->placeholder('12lxb3l')
                ->help('Ідентифікатор Reddit поста. 7 символів'),

            TextArea::make('post.title')
                ->maxlength(300)
                ->rows(3)
                ->title('Заголовок')
                ->placeholder('Україна запроваджує нові заходи для боротьби з корупцією: що зміниться?')
                ->help('До 300 символів.'),

            SimpleMDE::make('post.description')
                ->title('Опис')
                ->placeholder(
                    "Недавно в Україні сталася важлива подія, яка зацікавила багатьох громадян. Уряд країни прийняв рішення про введення нових законів щодо захисту прав споживачів. За цією ініціативою відбулася значна робота, в рамках якої було розглянуто багато аспектів та враховано думки різних зацікавлених сторін.

Нові закони повинні сприяти покращенню якості продукції та послуг, які пропонуються на ринку, а також забезпечити більш ефективний захист прав споживачів від недобросовісних продавців. Це дуже важлива новина для всіх громадян України, оскільки це рішення може допомогти збільшити рівень довіри між споживачами та продавцями та покращити стан економіки країни в цілому."
                ),


            Input::make('post.url')
                ->type('url')
                ->title('Контент (url)')
                ->placeholder('https://i.redd.it/leudcu9xcssa1.jpg')
                ->help('Посилання на контент з reddit (фото, гіф, відео).'),

            Link::make('Переглянути конент')
                ->title('')
                ->icon('link')
                ->canSee($this->query->has('post.url'))
                ->href($this->query->get('post.url') ?? '')
                ->target('_blank'),


            Input::make('post.permalink')
                ->type('url')
                ->title('Посилання на джерело')
                ->placeholder(
                    'https://www.reddit.com/r/ukraine/comments/12g1wkl/wa_maritime_museum/?utm_source=share&utm_medium=web2x&context=3'
                )
                ->help('Посилання на пост reddit.'),

            Link::make('Переглянути джерело')
                ->icon('link')
                ->canSee($this->query->has('post.permalink'))
                ->href($this->query->get('post.permalink') ?? '')
                ->target('_blank'),
        ];
    }
}
