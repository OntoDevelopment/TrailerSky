<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property mixed $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AppModel extends Model
{
    protected $guarded = [];
    
    protected function casts() : array {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function _makeAttributeMaxLength($length = false, $get = null) : Attribute
    {
        if($length === false){
            return Attribute::make();
        }
        if(!$get){
            $get = fn ($value) => $value;
        }
        return Attribute::make(
            set: fn (string $value) => substr(trim($value), 0 , $length),
            get: $get
        );
    }
}
