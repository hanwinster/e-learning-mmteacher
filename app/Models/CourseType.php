<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CourseType extends Model
{
	use Sortable;

    protected $fillable = [
    	'name'
    ];

    public static function getItemList()
	{
	    return (new static)::get()->pluck('name', 'id');
	}

	public function scopeWithSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%$search%");
    }
}
