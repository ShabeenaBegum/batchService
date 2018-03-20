<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Batch\Services\DueService;
use App\Student\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DueSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $req, DueService $service)
    {
        $this->validate($req, [
            "enroll_id" => ["required_without:batch_id",
                function($attribute, $value, $fail) {
                    $enroll_id_active = StudentBatch::where("enroll_id",$value)->first();
                    if ($enroll_id_active['status'] != config('constant.batch.status.active')) {
                        return $fail($attribute.' is not ACTIVE');
                    }
                }],
            "batch_id"  => "required_without:enroll_id"
        ]);
        try{
            return resOk($service->handle($req));
        } catch (\Exception $e){
            return resError($e);
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
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
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

    /**
     * @param Request $req
     * @param DueService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function dueSubmissionWithSessionId(Request $req, DueService $service)
    {
        $this->validate($req, [
            "enroll_id" => ["sometimes",
                function($attribute, $value, $fail) {
                    $enroll_id_active = StudentBatch::where("enroll_id",$value)->first();
                    if ($enroll_id_active['status'] != config('constant.batch.status.active')) {
                        return $fail($attribute.' is not ACTIVE');
                    }
                }],
            "session_id"  => "required"
        ]);
        try{
            return resOk($service->dueSubmissionFromSessionId($req));
        } catch (\Exception $e){
            return resError($e);
        }
    }


}
