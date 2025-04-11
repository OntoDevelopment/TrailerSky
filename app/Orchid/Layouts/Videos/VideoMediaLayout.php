<?php

namespace App\Orchid\Layouts\Videos;

use Orchid\Screen\Actions;
use Orchid\Screen\Fields;
use Orchid\Support\Color;

class VideoMediaLayout extends \Orchid\Screen\Layouts\Rows
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
            Fields\Input::make('tmdb_id')
                ->type('text')
                ->title('Set TMDB ID'),

            Actions\Button::make('Set')
                ->method('setTMDB')
                ->icon('icon-check')
                ->type(Color::SUCCESS),
        ];
    }
}
