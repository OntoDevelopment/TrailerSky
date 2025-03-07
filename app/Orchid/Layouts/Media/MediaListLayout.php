<?php

namespace App\Orchid\Layouts\Media;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use Orchid\Screen\Fields;
use Orchid\Screen\Actions;

class MediaListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'media';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->width('200px')
                ->render(function ($media) {
                    return $media->id;
                }),

            TD::make('title', 'Title')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($media) {
                    return $media->title;
                }),

            TD::make('media_type', 'Type')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($media) {
                    return $media->media_type;
                })->filter(TD::FILTER_SELECT, [
                    'movie' => 'Movie',
                    'tv' => 'TV Show',
                ]),

            TD::make('tmdb_popularity', 'Popularity')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($media) {
                    return $media->tmdb_popularity;
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($media) {
                    return Fields\Group::make([
                        Actions\Link::make('Edit')->icon('pencil')->route('platform.media.edit', $media->id),
                        Actions\Link::make('TMDB')->href($media->tmdb_url)->target('_blank'),
                    ]);
                }),
        ];
    }
}
