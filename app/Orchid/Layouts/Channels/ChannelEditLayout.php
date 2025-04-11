<?php

namespace App\Orchid\Layouts\Channels;

use Orchid\Screen\Fields;

class ChannelEditLayout extends \Orchid\Screen\Layouts\Rows
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
        return [];
    }
}
