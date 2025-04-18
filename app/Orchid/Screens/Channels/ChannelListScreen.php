<?php

namespace App\Orchid\Screens\Channels;

use App\Models\Channel;
use Orchid\Screen\Screen;

class ChannelListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $query = Channel::filters()->defaultSort('name');

        return [
            'channels' => $query->paginate(100),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Channels';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \App\Orchid\Layouts\Channels\ChannelListLayout::class,
        ];
    }
}
