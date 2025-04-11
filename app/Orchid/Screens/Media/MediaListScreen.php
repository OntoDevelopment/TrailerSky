<?php

namespace App\Orchid\Screens\Media;

use App\Models\Media;
use Orchid\Screen\Screen;

class MediaListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $query = Media::filters()->defaultSort('created_at', 'desc');

        return [
            'media' => $query->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Media';
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
            // \App\Orchid\Layouts\Media\MediaFiltersLayout::class,
            \App\Orchid\Layouts\Media\MediaListLayout::class,
        ];
    }
}
