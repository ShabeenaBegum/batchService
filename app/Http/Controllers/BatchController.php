<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Batch\Services\Batch\StatusChange;
use App\Batch\Services\Batch\View;
use App\Batch\Services\CreateService;
use App\Batch\Services\ExtraSession;
use App\Batch\Services\StatusChangeService;
use App\Batch\Services\UpdateService;
use App\Http\Requests\Batch\CreateRequest;
use App\Http\Requests\Batch\ExtraSession as ExtraSessionRequest;
use App\Http\Requests\Batch\SessionCancel;
use App\Http\Requests\Batch\StatusChange as StatusChangeRequest;
use App\Http\Requests\Batch\UpdateRequest;
use Exception;
use Illuminate\Http\Request;

class BatchController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param View $service
     * @return \Illuminate\Http\Response
     */
    public function index(View $service)
    {
        $data['batch_ids'] = request("ids", null);
        $batches = $service->handle($data);
        return resOk($batches);

    }

    /**
     * Store a newly created batch in storage.
     *
     * @param CreateRequest $request
     * @param CreateService $service
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function store(CreateRequest $request, CreateService $service)
    {
        try {
            $data = $request->all();
            $batch = $service->handle($data);
            return resOk($batch, 201);
        } catch (Exception $e) {
            info($e);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Batch $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        return resOk($batch);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Batch $batch
     * @param UpdateService $service
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Batch $batch, UpdateService $service)
    {
        try {
            $data = $request->all();
            $data['batch_details'] = $batch;
            $batch = $service->handle($data);
            return resOk($batch);
        } catch (Exception $e) {
            info($e);
            return resError();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Batch $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        try {
            $batch->delete();
            return resOk();
        } catch (Exception $e) {
            return resError();
        }
    }

    /**
     * @param Request $request
     * @param Batch $batch
     * @return \Illuminate\Http\JsonResponse
     */
    public function BatchStatusChange(StatusChangeRequest $request, Batch $batch, StatusChange $service)
    {

        try {
            $data = $request->all();
            $data['batch_details'] = $batch;
            $batch = $service->handle($data);
            return resOk($batch);
        } catch (Exception $e) {
            return resError();
        }
    }

    public function BatchExtraSession(ExtraSessionRequest $request, Batch $batch, ExtraSession $service)
    {
        try {
            $data = $request->all();
            $data['batch_details'] = $batch;
            $extraSession = $service->handle($data);
            return resOk($extraSession);
        } catch (Exception $e) {
            return resError(["message" => $e->getMessage()]);
        }
    }

    /**
     * @param SessionCancel $request
     * @param Batch $batch
     * @param StatusChangeService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function SessionCancel(SessionCancel $request, Batch $batch, StatusChangeService $service)
    {
        try {
            $data = $request->all();
            $data["batch_details"] = $batch;
            $cancelledSession = $service->handle($data);
            return resOk($cancelledSession);
        } catch (Exception $e) {
            return resError(["message" => $e->getMessage()]);
        }
    }


}
