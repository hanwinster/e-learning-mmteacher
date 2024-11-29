<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\ResourcesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Resource;
use App\Models\Keyword;
use App\Models\ResourcePrivacy;

class ImportResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:import_resource']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.import.resource');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $rules = [
            'uploaded_file' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            // 'batch_no' => 'required',
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'mimetypes' => 'The :attribute must be Excel file with .xlsx extension.'
        ];

        $this->validate($request, $rules, $messages);

        try {
            // $import = Excel::import(new UsersImport, request()->file('uploaded_file'));

            /*             $import = new UsersImport();
                        Excel::import($import, request()->file('uploaded_file')); */

            $collection = (new ResourcesImport)->toCollection(request()->file('uploaded_file'));

            $collection = $collection->first();

            $old_count = 0;
            $new_count = 0;
            $total = $collection->count();

            $columns = [
                'title',
                'description',
                'resource_format',
                'strand',
                'sub_strand',
                'lesson',
                'author',
                'publisher',
                'publishing_year',
                'publishing_month',
                'years',
                'keywords',
                'url',
                'additional_information',
                'license_id',
                'subjects',
                'accessible_rights',
                'approval_status',
                'published',
                'is_featured',
                'allow_feedback',
                'allow_download',
                'allow_edit',
            ];

            if (isset($collection) && $collection->count() > 0) {
                $firstRecord = $collection->first();

                if ($columns != $firstRecord->keys()->toArray()) {
                    return redirect()->route('admin.resource.bulk-import')
                        ->with('error', 'Invalid Excel format. '
                        . 'Please check the columns names. (' . implode(', ', $columns) . ')');
                }

                $collection->each(function ($row) use (&$old_count, &$new_count) {
                    $row = $row->toArray();

                    $old = Resource::where('title', $row['title'])
                    ->first();

                    if (!$old) {
                        $post = new Resource();
                        $post->title = $row['title'];
                        $post->slug = '';
                        $post->description = $row['description'];
                        $post->resource_format = strtolower($row['resource_format']);
                        $post->strand = $row['strand'];
                        $post->sub_strand = $row['sub_strand'];
                        $post->lesson = $row['lesson'];
                        $post->author = $row['author'];
                        // $post->rating = (int)$row['rating'];
                        $post->publisher = $row['publisher'];
                        $post->publishing_year = $row['publishing_year'];
                        $post->publishing_month = $row['publishing_month'];
                        //$post->published = $row['published'];
                        $post->published = (int)$row['published'];
                        $post->user_id = auth()->user()->id;
                        $post->license_id = (int)$row['license_id'];
                        $post->is_featured = (int)$row['is_featured'];
                        $post->url = $row['url'];
                        $post->additional_information = $row['additional_information'];
                        $post->allow_feedback = (int)$row['allow_feedback'];
                        $post->allow_download = (int)$row['allow_download'];
                        $post->allow_edit = (int)$row['allow_edit'];
                        $post->approval_status = Resource::APPROVAL_STATUS_APPROVED;
                        $post->approved = 1;
                        $post->approved_at = now();
                        $post->total_page_views = 0;
                        $post->total_downloads = 0;

                        $post->save();

                        if ($row['keywords']) {
                            Keyword::tagResource($post, explode(',', $row['keywords']), 'creator');
                        }

                        if ($row['subjects']) {
                            $row['subjects'] = explode(',', $row['subjects']);

                            if (count($row['subjects'])) {
                                $post->subjects()->sync($row['subjects']);
                            }
                        }

                        if ($row['years']) {
                            $row['years'] = explode(',', $row['years']);

                            if (count($row['years'])) {
                                $post->years()->sync($row['years']);
                            }
                        }

                        if ($row['accessible_rights']) {
                            $allowed_types = ['admin', 'manager', 'teacher_educator', 'student_teacher', 'guest'];

                            $privacies = explode(',', strtolower($row['accessible_rights']));
                            $privacies = array_intersect($allowed_types, $privacies);

                            if (count($privacies)) {
                                foreach ($privacies as $access) {
                                    $privacy = new ResourcePrivacy();
                                    $privacy->resource_id = $post->id;
                                    $privacy->user_type = $access;
                                    $privacy->save();
                                }
                            }
                        }

                        $new_count++;
                    } else {
                        $old_count++;
                    }
                });
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }

        $i = 0;

        return redirect()->route('admin.resource.bulk-import')
        ->with('success', "Found $total total records. $new_count record(s) inserted and $old_count duplicates skipped");
    }
}
