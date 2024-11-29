<?php

namespace App\Http\Controllers\Admin;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class MediaController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = Media::findOrFail($id);

        $row->delete();

        return Redirect::back()
            ->with('success', 'Successfully deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaByResource($id)
    {
        $row = Media::findOrFail($id);
        $row->delete();
        return 'Media has successfully deleted.';
        
    }
}
