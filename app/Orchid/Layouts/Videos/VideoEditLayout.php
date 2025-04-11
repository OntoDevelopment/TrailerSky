<?php

namespace App\Orchid\Layouts\Videos;

use Orchid\Screen\Actions;
use Orchid\Screen\Fields;
use Orchid\Support\Color;

class VideoEditLayout extends \Orchid\Screen\Layouts\Rows
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
     * @return \Orchid\Screen\Field[]
     */
    protected function fields(): iterable
    {
        return [
            Fields\Input::make('video.title')
                ->type('text')
                ->title('Title')
                ->readonly(),

            Fields\Select::make('video.type')
                ->options([
                    'trailer' => 'Trailer',
                    'teaser' => 'Teaser',
                    'other' => 'Other',
                    'undefined' => 'Undefined',
                ])
                ->title('Type')
                ->required(),

            Actions\Button::make('Save')
                ->method('save')
                ->icon('icon-check')
                ->type(Color::SUCCESS),
        ];
    }
}
