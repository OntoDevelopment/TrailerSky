<?php

namespace App\Actions\YouTube\Traits;

use App\Entities\YouTubeVideo;

use App\Models\Video;

trait ImportsVideos
{
    /**
     *
     * @param YouTubeVideo[] $results
     * @return Video[]
     */
    public function importVideos($results): array
    {
        $imported = [];
        foreach ($results as $YouTubeVideo) {
            if(is_array($YouTubeVideo)){
                $YouTubeVideo = new YouTubeVideo($YouTubeVideo);
            }
            
            $Video = Video::find($YouTubeVideo->id());
            if (!$Video) {
                $this->log(">   " . $YouTubeVideo->title());
                $Video = $YouTubeVideo->make();
            }
            $imported[] = $Video;
            if (in_array($Video->type, ['trailer', 'teaser'])) {
                $this->fetchDetails($Video);
            }
        }
        return $imported;
    }
}
