<?php

namespace App\Http\Controllers;

use App\Batch\BatchHelpers;
use App\Batch\Models\Batch;
use App\Batch\Models\Session;
use App\Batch\Services\StatusChangeService;
use App\Http\Requests\Session\Update;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *f
     * @param  \Illuminate\Http\Request $request
     * @param $session_id
     * @param StatusChangeService $service
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $session_id, StatusChangeService $service)
    {

        try{
            $data = $request->all();
            $data["session_id"] = $session_id;
            return resOk($service->handle($request));
        } catch (\Exception $e)
        {
            return resError(["message" => $e->getMessage()]);
        }

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
