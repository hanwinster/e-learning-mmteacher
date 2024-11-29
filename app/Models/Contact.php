<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Contact extends Model
{
    use Sortable;

    protected $table = 'contacts';

    const STATUS_PENDING = 0;
    const STATUS_CLOSED = 1;

    const STATUS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_CLOSED => 'Closed'
    ];

    public $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'phone_no',
        'organization',
        'region_state',
        'status',
    ];

    public $sortable = [
        'id',
        'name',
        'email',
        'phone_no',
        'organization',
        'region_state',
        'status',
        'created_at',
        'updated_at',
    ];

    const OUTSIDE_MYANMAR = 1;
    const KACHIN_STATE = 2;
    const KAYAH_STATE = 3;
    const KAYIN_STATE = 4;
    const CHIN_STATE = 5;
    const MON_STATE = 6;
    const RAKHINE_STATE = 7;
    const SHAN_STATE = 8;
    const YANGON_REGION = 9;
    const MANDALAY_REGION = 10;
    const MAGWAY_REGION = 11;
    const SAGAING_REGION = 12;
    const BAGO_REGION = 13;
    const AYEYARWADY_REGION = 14;
    const TANINTHAYI_REGION = 15;

    const REGIONS_STATES = [
        self::OUTSIDE_MYANMAR => 'Outside Myanmar',
        self::KACHIN_STATE => 'Kachin State',
        self::KAYAH_STATE => 'Kayah State',
        self::KAYIN_STATE => 'Kayin State',
        self::CHIN_STATE => 'Chin State',
        self::MON_STATE => 'Mon State',
        self::RAKHINE_STATE => 'Rakhine State',
        self::SHAN_STATE => 'Shan State',
        self::YANGON_REGION => 'Yangon Region',
        self::MANDALAY_REGION => 'Mandalay Region',
        self::MAGWAY_REGION => 'Magway Region',
        self::SAGAING_REGION => 'Sagaing Region',
        self::BAGO_REGION => 'Bago Region',
        self::AYEYARWADY_REGION => 'Ayeyarwady Region',
        self::TANINTHAYI_REGION => 'Taninthayi Region',
    ];

    public function getState()
    {
        if ($this->region_state) {
            return self::REGIONS_STATES[$this->region_state];
        }
    }

    public function getStatus()
    {
        return self::STATUS[$this->status];
    }

    public function scopeWithSearch($query, $keyword)
    {
        if ($keyword) {
            $query = $query->orWhere('name', 'LIKE', "%$keyword%");
            $query = $query->orWhere('email', 'LIKE', "%$keyword%");
            $query = $query->orWhere('subject', 'LIKE', "%$keyword%");
            return $query->orWhere('message', 'LIKE', "%$keyword%");
        } else {
            return $query;
        }
    }

    public function scopeWithStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        } else {
            return $query;
        }
    }

    public function scopeWithRegion($query, $region)
    {
        if ($region) {
            return $query->where('state_region', $region);
        } else {
            return $query;
        }
    }
}
