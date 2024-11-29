<?php

namespace App\Http\Controllers\API\Guest;

//use App\Http\Requests\RequestContactBackend as Request;
//use App\Http\Requests\RequestContactUs as Request;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    protected $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RequestContactBackend  $request     *
     * @return \Illuminate\Http\Response
     */
    public function saveContact(Request $request)
    {   
        if( count($request->all()) < 1) {
            return response()->json(['code' => 400, 'message' => 'Empty fields cannot be accepted'], 400);
        }
        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'subject' => 'required',
                'message' => 'required',
                'phone_no' => 'nullable',
                'organization' => 'nullable',
                'region_state' => 'nullable'
            ]);
    
        if ($validator->fails()) {
            return response()->json(['code' => 400, 'message' => 'Missing one or more of the mandatory fields'], 400);
        }
        $this->repository->saveRecord($request);
        return response()->json(['data' => 'Saved successfully']);
    }
}
