<?php

namespace App\Orchid\Layouts\Channels;

use Orchid\Screen\Actions;
use Orchid\Screen\Fields;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ChannelListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'channels';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->width('240px')
                ->render(function ($channel) {
                    return $channel->id;
                }),

            TD::make('name', 'Name')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($channel) {
                    return $channel->name;
                }),

            TD::make('actions', 'Actions')
                ->render(function ($channel) {
                    return Fields\Group::make([
                        Actions\Link::make('Edit')
                            ->route('platform.channels.edit', $channel->id)
                            ->icon('pencil'),
                    ]);
                }),
        ];
    }
}
