<?php

namespace App\Orchid\Layouts\Videos;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use Orchid\Screen\Fields;
use Orchid\Screen\Actions;

class VideoListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'videos';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->width('150px')
                ->render(function ($video) {
                    return $video->id;
                }),

            TD::make('title', 'Title')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($video) {
                    return $video->title;
                }),

            TD::make('type', 'Type')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($video) {
                    return $video->type;
                })->filter(TD::FILTER_SELECT, [
                    'trailer' => 'Trailer',
                    'teaser' => 'Teaser',
                    'other' => 'Other',
                    'undefined' => 'Undefined',
                ]),

            TD::make('tmdb', 'Media')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($video) {
                    if(!$video->media_id) {
                        return 'no media';
                    }
                    return Actions\Link::make('')
                        ->icon('bs.film')
                        ->route('platform.media.edit', $video->media_id);
                }),

            TD::make('created_at', 'Created')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($video) {
                    return $video->created_at->format('Y-m-d');
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('150px')
                ->render(function ($video) {
                    return Fields\Group::make([
                        Actions\Link::make('Edit')
                            ->icon('pencil')
                            ->route('platform.videos.edit', $video->id),

                        Actions\Link::make('')
                            ->icon('bs.youtube')
                            ->href($video->youtubeUrl())
                            ->target('_blank'),
                    ]);
                }),
        ];
    }
}
