<?php
namespace App\Traits;

use App;
use App\Utilities\Rabbit;
use Log;

trait Unicodeable
{
    public static function bootUnicodeable()
    {
        static::retrieved(function ($model) {
            if (App::isLocale('my-ZG')) {
                if ($fields = $model->unicodeFields) {
                    foreach ($fields as $field) {
                        $model->{$field} = (is_null($model->{$field}))? '' : Rabbit::uni2zg($model->{$field});
                    }
                }
            }
        });

        static::saving(function ($model) {
            if (App::isLocale('my-ZG')) {
                if ($fields = $model->unicodeFields) {
                    foreach ($fields as $field) {
                        if (isset($model->{$field}) && fontDetect($model->{$field}) == 'zawgyi') {
                            $model->{$field} = zg2uni($model->{$field});
                        }
                    }
                }
            }
        });
    }
}
