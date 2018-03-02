<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentBatch\Assign;
use App\Student\Models\StudentBatch;
use App\Student\Services\BatchAssign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentBatchController extends Controller
{
    /**
     * StudentBatchController constructor.
     */
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * Display a listing of the resource.
     *
     * @param $enrollId
     * @return \Illuminate\Http\Response
     */
    public function index($enrollId)
    {
        $data = StudentBatch::with("batch")->where("enroll_id", $enrollId)->get();

        if(count($data)){
            return resOk($data);
        }

        throw new ModelNotFoundException("No Batches are assigned to this enroll id");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Assign $request
     * @param $userId
     * @param BatchAssign $service
     * @return \Illuminate\Http\Response
     */
    public function store(Assign $request, $userId, BatchAssign $service)
    {
        return $service->handle($userId, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($enrollId, $batchId)
    {
        return resOk(StudentBatch::where("enroll_id", $enrollId)
            ->where("batch_id", $batchId)
            ->firstOrFail());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
