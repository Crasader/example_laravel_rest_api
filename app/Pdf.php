<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'custom_text',
        'filename',
        'link',
    ];
}
