<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourcePrivacy extends Model
{
    protected $table = 'resource_privacy';

    protected $fillable = [
        'resource_id',
        'user_type',
        'role_id'
    ];

    public function resource()
    {
        $this->belongsTo('App\Models\Resource', 'resource_id');
    }
}
