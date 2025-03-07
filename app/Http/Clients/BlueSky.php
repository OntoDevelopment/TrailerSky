<?php

namespace App\Http\Clients;

use App\Models\Video;
use App\Models\Post;

class BlueSky
{
    private static $api;
    private static $postService;

    public static function api(): \potibm\Bluesky\BlueskyApi
    {
        if (!self::$api) {
            self::$api = new \potibm\Bluesky\BlueskyApi(env('BLUESKY_HANDLE'), env('BLUESKY_APP_PASSWORD'));
        }
        return self::$api;
    }
    public static function postService(): \potibm\Bluesky\BlueskyPostService
    {
        if (!self::$postService) {
            self::$postService = new \potibm\Bluesky\BlueskyPostService(self::api());
        }
        return self::$postService;
    }

    public static function initDraft($content): Draft
    {
        $Draft = Draft::create($content);
        $Draft = self::postService()->addFacetsFromTags($Draft);
        return $Draft;
    }

    protected static function createPost(Draft $Draft): \potibm\Bluesky\Response\RecordResponse
    {
        return self::api()->createRecord($Draft);
    }

    public static function post(Post $Post) : \potibm\Bluesky\Response\RecordResponse
    {
        $Video = Video::find($Post->video_id);
        $Draft = self::initDraft($Post->content);
        $Draft = $Draft->attachYouTubeVideo($Video);
        $response = self::createPost($Draft);
        return $response;
    }
}

class Draft extends \potibm\Bluesky\Feed\Post
{
    public static function create(string $text, string $lang = 'en'): self
    {
        $Draft = new self();
        $Draft->setText($text);
        $Draft->setLangs([$lang]);

        return $Draft;
    }

    public function attachYouTubeVideo(Video $Video)
    {
        // Attach Website Card
        return BlueSky::postService()->addWebsiteCard($this, $Video->youtubeUrl(), $Video->title, $Video->description, $Video->youtubeThumbnailPath());
    }
}