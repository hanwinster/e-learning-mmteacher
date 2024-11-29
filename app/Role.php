<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

class Role extends \Spatie\Permission\Models\Role
{
    use Sortable;

    protected $table = 'roles';

    public $sortable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'LIKE', "%$search%");
        } else {
            return $query;
        }
    }
}
