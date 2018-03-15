<?php

namespace App\Http\Controllers;

use App\Student\Models\StudentBatch;
use Illuminate\Http\Request;

class SearchSubmissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $req
     * @param $search
     * @return $student_batch
     */
    public function index(Request $req, $search)
    {

        $this->validate($req, [
            "enroll_id" => "required",
            "session_id" => "sometimes"
        ]);
        $student_batch = StudentBatch::where('enroll_id',$req->get('enroll_id'))->first();
        if($search == "all"){
            if($req->has('enroll_id') && $req->has('session_id')){
                return $student_batch['sessions']->where('session_id',$req->get('session_id'))->first();
            } else if($req->has('enroll_id')) {
                return $student_batch['sessions'];
            }
        } else {
            if($req->has('enroll_id') && $req->has('session_id')){
                return $student_batch['sessions']->where('session_id',$req->get('session_id'))->pluck($search);
            }
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Request $req
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req)
    {
        $this->validate($req, [
            "batch_id" => "required",
            "session_id" => "sometimes"
        ]);
        $student_batch = StudentBatch::where('batch_id',$req->get('batch_id'))->get(['sessions']);
        if($req->has('session_id')){
            return $student_batch->where('sessions.session_id',$req->get('session_id'))->get();
        }
        return $student_batch;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
