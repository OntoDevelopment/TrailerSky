<?php

namespace App\Orchid\Layouts\Videos;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class VideoFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [];
    }
}
