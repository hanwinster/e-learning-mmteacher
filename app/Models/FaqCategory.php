<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\Unicodeable;

class FaqCategory extends Model
{
    use Sortable, Sluggable, Unicodeable;

    protected $table = 'faq_categories';

    protected $fillable = ['title', 'slug', 'body'];

    public $sortable = [
        'id',
        'title',
        'created_at',
        'updated_at'
    ];

    public $unicodeFields = [
        'title',
        'body',
    ];

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

    /**
     * Eloquent Relation for \App\Models\Company
     *
     * @return void
     */
    public function faqs()
    {
        return $this->hasMany('App\Models\Faq', 'category_id');
    }

    public function scopeIsCategory($query, $slug = null)
    {
        return $query->with(['company' => function ($q) {
            $q->isPublished();
        }]);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            return $query->where('title', 'LIKE', "%$search%");
        } else {
            return $query;
        }
    }

    public function path()
    {
        return route('faq-category.show', $this->slug);
    }
}
