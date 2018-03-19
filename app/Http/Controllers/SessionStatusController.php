<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Batch\Models\Session;
use App\Batch\Services\Sessions\StatusStore;
use App\Batch\Services\Sessions\StatusView;
use App\Events\Session\Completed;
use Illuminate\Http\Request;

class SessionStatusController extends Controller
{
    /**
     * SessionStatusController constructor.
     */
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * Display a listing of the resource.
     *
     * @param $sessionId
     * @param StatusView $service
     * @return \Illuminate\Http\Response
     */
    public function index($sessionId, StatusView $service)
    {
        $session = $service->handle(["session_id" => $sessionId]);
        return resOk($session);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $sessionId
     * @return \Illuminate\Http\Response
     */
    public function store($sessionId, StatusStore $service)
    {
        $session = $service->handle([
            "session_id" => $sessionId,
            "completed_date" => request("completed_date", null)
        ]);
        event(new Completed($session));
        return resOk($session, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
