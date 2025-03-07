<?php

namespace App\Orchid\Screens\Media;

use App\Models\Media;

use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

/**
 * @property Media $media
 */
class MediaEditScreen extends \App\Orchid\Screens\AppScreen
{
    public $media;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Media $media): iterable
    {
        return [
            'media' => $media,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {

        return $this->media->title;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            \Orchid\Screen\Actions\Button::make('Sync Media')
                ->icon('sync')
                ->method('runSyncMedia'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \App\Orchid\Layouts\Media\MediaEditLayout::class,
        ];
    }

    public function save(Media $media, Request $request)
    {
        $request->validate([
            'media.title' => 'required',
        ]);
        $media->fill($request->input('media'));
        $media->save();
        Toast::info('Media was saved');
    }

    public function runSyncMedia()
    {
        $this->runAction(\App\Actions\TMDB\UpdateMedia::class, ['id' => $this->media->id]);
    }
}
