<?php

namespace App\Http\Controllers;

use App\Http\Requests\Submission\Search;
use App\Http\Requests\Submission\SearchAll;
use App\Student\Models\StudentBatch;
use App\Student\Services\Submission\Search as SearchService;
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
    public function index(Search $req, $search, SearchService $service)
    {
        $data = $req->all();
        $data['search'] = $search;
        $sessions = $service->handle($data);
        return resOk($sessions);
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
    public function show(SearchAll $req)
    {
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
