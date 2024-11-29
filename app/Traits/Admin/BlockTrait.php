<?php

namespace App\Traits\Admin;

use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use File;

trait BlockTrait
{
    public function saveRecord($request, $row)
    {
        $row->fill($request->only([
            'title', 'hide_title', 'region', 'conditions', 'body', 'published', 'type', 'weight'
        ]));

        if ($request->input('published') !== null) {
            $row->published = $request->input('published', false);
        }

        $row->save();

        if ($request->file('uploaded_file')) {
            $row->addMediaFromRequest('uploaded_file')->toMediaCollection('blocks');
        }
    }
}
