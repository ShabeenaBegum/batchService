<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Batch\Services\DueService;
use App\Batch\Services\DueServiceSession;
use App\Http\Requests\DueSubmission;
use App\Http\Requests\DueSubmissionSession;
use App\Student\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DueSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DueSubmission $request
     * @param DueService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DueSubmission $request, DueService $service)
    {
        try{
            $dueSubmission = $service->handle($request->all());
            return resOk($dueSubmission);
        } catch (\Exception $e){
            return resError(["message" => $e->getMessage()]);
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
     * @param DueSubmissionSession $request
     * @param DueServiceSession $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function dueSubmissionWithSessionId(DueSubmissionSession $request, DueServiceSession $service)
    {
        try{
            $dueSubmission = $service->handle($request);
            return resOk($dueSubmission);
        } catch (\Exception $e){
            return resError($e);
        }
    }


}
