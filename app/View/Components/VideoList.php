<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Video;

use Illuminate\Support\Facades\Cache;

class VideoList extends Component
{
    public $videos;
    public $sql;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $scope)
    {
        $query = Video::scopes($scope);
        $this->videos = Cache::remember(hash_query($query), 5 * 60, fn() => $query->get());
        //$this->sql = $query->toSql();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.video-list');
    }
}
