<?php

namespace App\Http\Clients;

use App\Models\Video;

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

    public static function initPost($content): Post
    {
        $Post = Post::create($content);
        $Post = self::postService()->addFacetsFromTags($Post);
        return $Post;
    }

    protected static function createPost(Post $post): \potibm\Bluesky\Response\RecordResponse
    {
        return self::api()->createRecord($post);
    }

    public static function post(Video $Video) : \potibm\Bluesky\Response\RecordResponse
    {
        $Post = self::initPost($Video->postGenerate(300, false));
        $Post = $Post->attachYouTubeVideo($Video);
        $response = self::createPost($Post);
        return $response;
    }
}

class Post extends \potibm\Bluesky\Feed\Post
{
    public static function create(string $text, string $lang = 'en'): self
    {
        $post = new self();
        $post->setText($text);
        $post->setLangs([$lang]);

        return $post;
    }

    public function attachYouTubeVideo(Video $Video)
    {
        // Attach Website Card
        return BlueSky::postService()->addWebsiteCard($this, $Video->youtubeUrl(), $Video->title, $Video->description, $Video->youtubeThumbnailPath());
    }
}