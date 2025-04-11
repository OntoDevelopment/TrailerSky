<?php

namespace App\View\Components;

use App\Models\Hashtag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class HashtagList extends Component
{
    public $hashtags;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $scope)
    {
        $query = Hashtag::scopes($scope);
        $this->hashtags = Cache::remember(hash_query($query), 5 * 60, fn () => $query->get());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.hashtag-list');
    }
}
