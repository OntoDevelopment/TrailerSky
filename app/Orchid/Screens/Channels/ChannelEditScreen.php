<?php

namespace App\Orchid\Screens\Channels;

use App\Models\Channel;
use Orchid\Screen\Screen;

/**
 * @property Channel $channel
 */
class ChannelEditScreen extends Screen
{
    public $channel;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Channel $channel): iterable
    {
        return [
            'channel' => $channel,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->channel->exists
            ? 'Edit Channel'
            : 'Create Channel';
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
            \App\Orchid\Layouts\Channels\ChannelEditLayout::class,
        ];
    }
}
