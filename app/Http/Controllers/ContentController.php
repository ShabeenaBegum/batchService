<?php

namespace App\Http\Controllers;

use App\Batch\Services\ContentService;
use App\Student\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ContentService $service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ContentService $service)
    {
        $this->validate($request,[
            'session_id' => 'required',
            'enroll_id'  => 'required',
            'user_id'    => 'sometimes',
            'content_id' => 'required',
            'submission_link' => 'required',
            'content_type' => 'required']);
        $type = $request->get('content_type');
        $student_batch = StudentBatch::where('sessions.session_id',$request->get('session_id'))
            ->where('enroll_id',$request->get('enroll_id'))
            ->first();
        $session = $student_batch->sessions->where("session_id", $request->get('session_id'))->first();
        $this->validate($request,[
            'content_id' => [
                'required',
                function($attribute, $value, $fail) use($session,$type,$request) {
                    $check_submitted = collect($session->$type)->where($type."_id",$request->get('content_id'))->first();
                    if (count($check_submitted) != 0) {
                        return $fail($request->get('content_id').' is already submitted.');
                    }
                },
            ],
        ]);
        try{
            return resOk($service->handle($request,$session,$student_batch,$type));
        } catch (\Exception $e)
        {
            return resError();
        }

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
