<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CourseEvaluation extends Model
{
    use Sortable;
    const EVALUATION_TYPES = [
        'agree_disagree' => 'Agree To Disagree',
        'excellent_poor' => 'Very Good To Very Poor',
        'likely_unlikely' => 'Very Likely To Very Unlikely',
        'device_options' => 'Device Options',
        'comment_box' => 'Comment',
    ];
    
    const AGREE_LEVELS = [ 
        5 => 'Strongly Agree',
        4 => 'Agree',
        3 => 'Neutral',
        2 => 'Disagree', 
        1 => 'Strongly Disagree'
    ];

    const EXCELLENT_LEVELS = [
        5 => 'Very Good',
        4 => 'Good',
        3 => 'Neutral',
        2 => 'Poor',
        1 => 'Very Poor'
    ];

    const LIKELY_LEVELS = [
        5 => 'Very likely',
        4 => 'Likely',
        3 => 'Neutral',
        2 => 'Unlikely',
        1 => 'Very unlikely'
    ];

    const DEVICE_OPTIONS = [
        1 => 'Mobile phone',
        2 => 'Laptop',
        3 => 'Tablet',
        4 => 'Desktop'
    ];


    protected $fillable = [
    	'question', 'question_mm','order', 'type'
    ];

    public static function getItemList()
	{
	    return (new static)::get()->pluck('question','id');
	}
    
	public function scopeWithSearch($query, $search)
    {
        return $query->where('question', 'LIKE', "%$search%");
    }
}
