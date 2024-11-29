<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ApprovalRequestComment extends Model
{
    use Sortable;

    protected $table = 'request_comments';

    protected $fillable = [
        'request_id', 'user_id', 'description', 'approval_status'
        ];

    public function scopeWithRequest($query, $request_id)
    {
        return $query->where('request_id', $request_id);
    }

    public function scopeWithUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
