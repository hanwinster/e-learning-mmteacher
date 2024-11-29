<?php

namespace App\Http\Controllers\Member;

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
    {   //echo $id;exit;
        $row = Media::findOrFail($id);

        $row->delete();
       // $this->deleteMediaWithNoRecord('user_assignment_attached_file');
        return Redirect::back()
            ->with('success', 'Successfully deleted');
    }

    protected function deleteMediaWithNoRecord($collectionName) //should be called when it's sure to delete a collection of files only
    {
        $rows = Media::where('collection_name',$collectionName)->get();
        if ( $rows && count($rows) ) {
            foreach( $rows as $row ) {
                $row->delete();
            }
        } 
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
    public function syncGDrive()
    {
        $row = Media::where('custom_properties', 'not like', '%gdrive_link%')->where('mime_type','!=','video/mp4')
        ->where('mime_type','!=','audio/mp4')->limit(50)->orderBy('id','desc')->get();
        // dd($row->toArray());
        $_nrow = [];
        foreach ($row->toArray() as $val) {
            if (file_exists(public_path("storage/" . $val['id'] . "/" . $val['file_name']))) {

                $client = new \Google_Client();
                $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
                $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
                $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
                $service = new \Google_Service_Drive($client);

                // $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                //     'name' => 'ExpertPHP',
                //     'mimeType' => 'application/vnd.google-apps.folder'));
                // $folder = $service->files->create($fileMetadata, array(
                //     'fields' => 'id'));
                // printf("Folder ID: %s\n", $folder->id);

                // $fileName = $request->file('resource_file')->getClientOriginalName();
                $file = new \Google_Service_Drive_DriveFile(array(
                    'name' => $val['file_name'],
                    'parents' => array(env('GOOGLE_DRIVE_FOLDER_ID'))
                ));
                $result = $service->files->create($file, array(
                    'data' => file_get_contents(public_path("storage/" . $val['id'] . "/" . $val['file_name'])),
                    'mimeType' => 'application/octet-stream',
                    'uploadType' => 'media'
                ));


                $val['custom_properties']['gdrive_link'] = 'https://drive.google.com/open?id=' . $result->id;
                // $val['custom_properties']['gdrive_link'] = 'https://drive.google.com/open?id=';
                $_nrow = $val;
                $_rup = Media::findOrFail($val['id']);
                $_rup->update($_nrow);

                // dd($_nrow);
                // echo $val['id'] ." here<br/>";
            }
        }
        $count = Media::where('custom_properties', 'not like', '%gdrive_link%')->orderBy('id','desc')->get()->count();
        echo "total need to complete sync: ".$count;
    }
}
