<?php

namespace App\Orchid\Screens\Videos;

use App\Models\Video;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

/**
 * @property Video $video
 */
class VideoEditScreen extends Screen
{
    public $video;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Video $video): iterable
    {
        return [
            'video' => $video,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        // you should never be creating a video...
        return $this->video->exists ? 'Edit Video' : 'Create Video';
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
            \App\Orchid\Layouts\Videos\VideoEditLayout::class,
            \App\Orchid\Layouts\Videos\VideoMediaLayout::class,
        ];
    }

    // save
    public function save(Video $video, Request $request)
    {
        $request->validate([
            'video.type' => 'required',
        ]);
        $video->type = $request->input('video.type');
        $video->save();
        Toast::info('Video was saved');
    }

    public function setTMDB(Video $video, Request $request)
    {
        $video->media_id = null;
        $video->save();

        $Action = \App\Actions\SetTMDB::exec([
            'id' => $video->id,
            'tmdb_id' => $request->input('tmdb_id'),
        ]);
        $error = false;
        foreach ($Action->log as $log) {
            if ($log->error) {
                $error = true;
                Toast::error($log->message);
            } else {
                Toast::info($log->message);
            }
        }

        if (! $error) {
            return redirect()->route('platform.videos');
        }
    }
}
