<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CourseLevel extends Model
{
    use Sortable;
    protected $fillable = [
    	'name', 'value'
    ];

    public static function getItemList()
	{
	    return (new static)::get()->pluck('value','id');
	}
    
	public function scopeWithSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%$search%");
    }
}
