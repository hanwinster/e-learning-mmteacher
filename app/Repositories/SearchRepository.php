<?php

namespace App\Repositories;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\User;

class SearchRepository
{
    protected $model;

    public function __construct(Resource $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->model->newQuery();
        //$query = $this->model; //->newQuery();

        $query->with(['subjects', 'media', 'years', 'user', 'license']);

        $LIKE = config('cms.search_operator');
// dd($request->input('search'));
        if ($search = $request->input('search')) {
            $search = mm_search_string($search);
            // $value = format_like_query($search);
            $value = $search;
            // dd($value);
            $query->orWhere('title', $LIKE, $value);
            $query->orWhere('author', $LIKE, $value);
            $query->orWhere('publisher', $LIKE, $value);
            $query->orWhere('description', $LIKE, $value);
            $query->orWhere('strand', $LIKE, $value);
            $query->orWhere('sub_strand', $LIKE, $value);
            $query->orWhere('lesson', $LIKE, $value);

            // Supports Keyword search
            $query->orWhereHas('keywords', function ($q) use ($search) {
                $q->whereIn('keyword', [$search]);
            });
        }

        if ($title = $request->input('title')) {
            $title = mm_search_string($title);
            // $value = format_like_query($title);
// dd($value);
            $query->where('title', $LIKE, $title);
        }

        if ($author = $request->input('author')) {
            $author = mm_search_string($author);
            $value = format_like_query($author);

            $query->where('author', $LIKE, $value);
        }

        if ($strand = $request->input('strand')) {
            $strand = mm_search_string($strand);
            $value = format_like_query($strand);

            $query->where('strand', $LIKE, $value);
        }

        if ($sub_strand = $request->input('sub_strand')) {
            $sub_strand = mm_search_string($sub_strand);
            $value = format_like_query($sub_strand);

            $query->where('sub_strand', $LIKE, $value);
        }

        if ($lesson = $request->input('lesson')) {
            $lesson = mm_search_string($lesson);
            $value = format_like_query($lesson);

            $query->where('lesson', $LIKE, $value);
        }

        if ($resource_format = $request->input('resource_format')) {
            if (is_array($resource_format)) {
                $query->whereIn('resource_format', $resource_format);
            } else {
                $query->whereIn('resource_format', explode(',', $resource_format));
            }
        }

        if ($license = $request->input('license')) {
            $query->where('license_id', '=', $license);
        }

        if ($subject = $request->input('subject')) {
            $query->whereHas('subjects', function ($q) use ($subject) {
                if (is_array($subject)) {
                    $q->whereIn('subject_id', $subject);
                } else {
                    $q->whereIn('subject_id', explode(',', $subject));
                }
            });
        }

        if ($years = $request->input('year')) {
            $query->whereHas('years', function ($q) use ($years) {
                if (is_array($years)) {
                    $q->whereIn('year_id', $years);
                } else {
                    $q->whereIn('year_id', explode(',', $years));
                }
            });
        }

        if ($keywords = $request->input('keywords')) {
            $query->whereHas('keywords', function ($q) use ($keywords) {
                if (is_array($keywords)) {
                    $q->whereIn('keyword', $keywords);
                } else {
                    $q->whereIn('keyword', explode(',', $keywords));
                }
            });
        }
        /*
        if ($accessible_right = $request->input('accessible_right')) {
            $query->whereHas('privacies', function ($q) use ($accessible_right) {
                $q->where('user_type', $accessible_right);
            });
        }
         */
        $query = $query->privacyFilter(currentUserType());
        $query->isPublished();
        $query->isApproved();

        $posts = $query->latest()->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }
}
