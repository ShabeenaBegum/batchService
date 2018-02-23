<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Batch\Services\CreateService;
use App\Batch\Services\UpdateService;
use App\Http\Requests\Batch\CreateRequest;
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
     * @param Request $req
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if ($req->has("ids")) {
            $id_array = explode(',', $req->get('ids'));
            return resOk(Batch::whereIn('_id', $id_array)->get());
        }
        return resOk(Batch::paginate(10));
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
            return resOk($service->handle($data), 201);
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
            return resOk($service->handle($data, $batch));
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
            return resOk($batch->delete());
        } catch (Exception $e) {
            return resError();
        }
    }
}
