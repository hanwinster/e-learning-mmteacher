<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\SoftDeletes; // KHK

class Subject extends Model implements HasMedia
{
	use Sluggable, Sortable, HasMediaTrait, SoftDeletes; // KHK

    protected $table = 'subjects';
	
	protected $dates = ['deleted_at']; // KHK
	
    protected $fillable = ['title', 'slug', 'published', 'weight']; // KHK

	public $sortable = [
        'id',
        'title',
		'weight',
        'created_at',
        'updated_at'
    ];
	
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource', 'resource_subject', 'resource_id', 'subject_id');
    }
	
	/* KHK Start */
	public function users()
    {
        return $this->belongsToMany(User::class);
    }
	/* KHK End */

    public function scopeIsPublished($query)
    {
		//return $query->where('published', true)->select('id', 'title'); 
        return $query->where('published', true); 
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->where('title', 'LIKE', "%$search%");
    }

    public function path()
    {
        return route('subject.show', $this->slug);
		//return 'subject/' . $this->slug; // KHK
    }
}
