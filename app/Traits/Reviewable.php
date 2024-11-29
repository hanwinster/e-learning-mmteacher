<?php
namespace App\Traits;

trait Reviewable
{
    /**
     * This model has many ratings.
     *
     * @return Review
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'resource_id')->orderBy('created_at', 'DESC');
    }

    public function averageRating()
    {
        return number_format($this->reviews()->avg('rating'), 1);
    }

    public function sumRating()
    {
        return $this->reviews()->sum('rating');
    }

    public function userAverageRating()
    {
        return $this->reviews()->where('user_id', auth()->id())->avg('rating');
    }

    public function userSumRating()
    {
        return $this->reviews()->where('user_id', auth()->id())->sum('rating');
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->reviews()->count();
        $total = $this->sumRating();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

    public function getSumRatingAttribute()
    {
        return $this->sumRating();
    }

    public function getUserAverageRatingAttribute()
    {
        return $this->userAverageRating();
    }

    public function getUserSumRatingAttribute()
    {
        return $this->userSumRating();
    }
}
