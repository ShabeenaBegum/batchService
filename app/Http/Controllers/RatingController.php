<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Events\Student\SessionRated;
use App\Student\Models\StudentBatch;
use App\Student\Services\Rating\Add;
use App\Student\Services\Rating\Session;
use App\Student\Services\Rating\Show;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RatingController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * Display a listing of the resource.
     *
     * @param $enrollId
     * @param $sessionId
     * @return Response
     */
    public function index($enrollId, $sessionId, Show $service)
    {
        $session = $service->handle([
            'enroll_id' => $enrollId,
            'session_id' => $sessionId
        ]);

        return resOk($session);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $sessionId
     * @return array
     */
    public function session($sessionId, Session $service)
    {
        $session = $service->handle(['session_id' => $sessionId]);
        return resOk($session);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $enrollId
     * @param $sessionId
     * @param Add $service
     * @return array
     */
    public function store($enrollId, $sessionId, Add $service)
    {
        $this->validate(\request(), [
            "rating" => "required"
        ]);

        $session = $service->handle([
            "enroll_id" => $enrollId,
            "session_id" => $sessionId,
            "rating" => request()->all()
        ]);

        event(new SessionRated($sessionId, request("rating"), request("comment")));

        return resOk($session, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $batchId
     * @return Response
     */
    public function batch($batchId)
    {
        $batch = Batch::findOrFail($batchId);
        //unset($batch['sessions']);
        return resOk($batch);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
