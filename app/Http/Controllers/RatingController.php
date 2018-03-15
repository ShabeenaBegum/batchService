<?php

namespace App\Http\Controllers;

use App\Batch\Models\Batch;
use App\Events\Student\SessionRated;
use App\Student\Models\StudentBatch;
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
    public function index($enrollId, $sessionId)
    {
        $studentBatch = StudentBatch::where("enroll_id", $enrollId)->where("sessions.session_id", $sessionId)->first();
        $session =  $studentBatch->sessions->where("session_id", $sessionId)->first();
        return resOk($session);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $sessionId
     * @return array
     */
    public function session($sessionId)
    {
        $batch = Batch::where("sessions._id", $sessionId)->firstOrFail();
        $session = $batch->sessions->where("_id", $sessionId)->first();
        return resOk($session);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $enrollId
     * @param $sessionId
     * @return array
     */
    public function store(Request $request, $enrollId, $sessionId)
    {
        $studentBatch = StudentBatch::where("enroll_id", $enrollId)->where("sessions.session_id", $sessionId)->first();
        $session = $studentBatch->sessions->where("session_id", $sessionId)->first();
        $rating = $request->all();
        $rating['rated_on'] = utcnow();
        $session->rating = $rating;
        $session->save();

        event(new SessionRated($sessionId, $request->get("rating"), $request->get("comment")));

        return resOk($session, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
