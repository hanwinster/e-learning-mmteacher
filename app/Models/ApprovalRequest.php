<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Traits\Unicodeable;

class ApprovalRequest extends Model
{
    use Sortable, Unicodeable;

    protected $table = 'requests';

    protected $fillable = ['resource_id', 'user_id', 'description', 'approval_status'];

    public $unicodeFields = [
        'description',
    ];

    public function resource()
    {
        return $this->belongsTo('App\Models\Resource', 'resource_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function comments()
    {
        return $this->belongsTo('App\Models\ApprovalRequestComment', 'request_id');
    }

    public function getCardCss()
    {
        switch ($this->approval_status) {
            case Resource::APPROVAL_STATUS_APPROVED:
            case Resource::APPROVAL_STATUS_REJECTED:
                return 'light'; // border-primary
                break;

            case Resource::APPROVAL_STATUS_PENDING:
                // https://getbootstrap.com/docs/4.0/components/card/
                return 'primary'; // border-primary
                break;
        }
    }

    public function scopeWithResource($query, $resource_id)
    {
        return $query->where('resource_id', $resource_id);
    }

    public function scopeWithUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeWithApprovalStatus($query, $approval_status)
    {
        if ($approval_status != null) {
            return $query->where('approval_status', $approval_status);
        }

        return $query;
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $query->whereHas('resource', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");
            });
        }

        return $query;
    }
}
