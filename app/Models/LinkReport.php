<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LinkReport extends Model
{
    use Sortable;

    const TYPE_INAPPROPRIATE_CONTENT = 1;
    const TYPE_BROKEN_LINK = 2;

    const TYPES = [
        self::TYPE_INAPPROPRIATE_CONTENT => 'Inappropriate Content',
        self::TYPE_BROKEN_LINK => 'Broken Link'
    ];

    const STATUS_PENDING = 0;
    const STATUS_CLOSED = 1;

    const STATUS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_CLOSED => 'Closed'
    ];

    protected $table = 'link_reports';

    protected $fillable = ['resource_id', 'user_id', 'report_type', 'comment'];

    public function resource()
    {
        return $this->belongsTo('App\Models\Resource', 'resource_id', 'id'); //->withoutTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getTypeName()
    {
        if ($this->report_type) {
            return self::TYPES[$this->report_type];
        }

        return null;
    }

    public function getStatusName()
    {
        if ($this->status !== null) {
            return self::STATUS[$this->status];
        }

        return null;
    }

    public function scopeWithResource($query, $resource_id)
    {
        return $query->where('resource_id', $resource_id);
    }

    public function scopeWithUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $query->whereHas('resource', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");
            });

            $query->orWhereHas('user', function ($q) use ($search) {
                $q->orWhere('name', 'LIKE', "%$search%");
                $q->orWhere('username', 'LIKE', "%$search%");
            });
        }

        return $query;
    }
}
