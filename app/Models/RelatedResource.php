<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedResource extends Model
{
    protected $table = 'related_resources';

    public function resource()
    {
        return $this->belongsTo('App\Models\Resource', 'related_resource_id');
    }
}
