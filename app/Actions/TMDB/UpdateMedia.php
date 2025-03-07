<?php

namespace App\Actions\TMDB;

use App\Models\Media;

use \Illuminate\Support\Carbon;

class UpdateMedia extends \App\Actions\AbstractAction
{

    use \App\Actions\Traits\FetchesDetails;

    public function run($params = [])
    {
        if (isset($params['id'])) {
            $media = Media::find($params['id']);
            if (!$media) {
                $this->log("Media not found: {$params['id']}", true);
                return;
            }
            $this->syncTMDB($media);
        } else {
            $this->log("Updating all media with TMDB ID and release date in the future and not updated in the last day");
            $query = Media::whereNotNull('release_date')
                ->whereNotNull('tmdb_id')
                ->where('release_date', '>', Carbon::now())
                ->where('updated_at', '<', Carbon::now()->subDays(1));

            foreach ($query->get() as $media) {
                $this->syncTMDB($media);
            }
        }
        $this->log("Done at " . Carbon::now());
    }
}
