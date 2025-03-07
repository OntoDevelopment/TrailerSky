<?php

namespace App\Orchid\Layouts\Media;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;

use Orchid\Screen\Fields;
use Orchid\Screen\Actions;
use Orchid\Support\Color;

class MediaEditLayout extends Rows
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
     */
    protected function fields(): iterable
    {
        return [
            Fields\Input::make('media.title')
                ->type('text')
                ->title('Title'),

            Actions\Button::make('Save')
                ->method('save')
                ->icon('icon-check')
                ->type(Color::SUCCESS),
        ];
    }
}
