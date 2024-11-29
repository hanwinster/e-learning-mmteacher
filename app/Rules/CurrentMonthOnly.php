<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class CurrentMonthOnly implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $carbon = Carbon::now();
        $month = request()->input('publishing_month');
        $currentMonth = intval($carbon->format('Ym'));
        $month = \str_pad($month, 2, '0', STR_PAD_LEFT);

        return request()->input('publishing_year').$month <= $currentMonth;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $carbon = Carbon::now();
        $currentMonth = $carbon->format('Y-m');

        return 'Publishing month should be less than '.$currentMonth.'.';
    }
}
