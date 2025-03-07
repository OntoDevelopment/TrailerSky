<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Support\Facades\Cache;

/**
 * @property mixed $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AppModel extends Model
{

    use \Orchid\Filters\Filterable;
    
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function _makeAttributeMaxLength($length = false, $get = null): Attribute
    {
        if ($length === false) {
            return Attribute::make();
        }
        if (!$get) {
            $get = fn($value) => $value;
        }
        return Attribute::make(
            set: fn(string $value) => sublen(asci_chars($value), $length),
            get: $get
        );
    }
}
