<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Traits\Unicodeable;

class Faq extends Model
{
    use Sortable, Unicodeable;

    protected $table = 'faqs';

    protected $fillable = [
        'question', 'answer', 'question_mm', 'answer_mm', 'published', 'category_id'
    ];

    public $unicodeFields = [
        'question',
        'answer',
    ];

    public $sortable = [
        'id',
        'question',
        'category_id',
        'created_at',
        'updated_at'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Eloquent Relation for \App\Models\FaqCategory
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo('App\Models\FaqCategory', 'category_id');
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeIsCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }

    public function scopeWithCategory($query, $category_id)
    {
        if ($category_id) {
            return $query->where('category_id', $category_id);
        } else {
            return $query;
        }
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            return $query->where('question', 'LIKE', "%$search%");
        } else {
            return $query;
        }
    }

    public function path()
    {
        return 'faq/'.$this->id;
    }
}
