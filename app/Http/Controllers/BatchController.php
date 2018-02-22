<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Batch\Services\CreateService;
use App\Batch\Services\UpdateService;
use App\Exceptions\BatchCreation;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->has("ids")){
            try{
                $id_array = explode(',', $req->get('ids'));
                return(Batch::whereIn('_id',$id_array)->get());
            } catch (Exception $e){
                info($e);
            }
        }else{
            return resOk(Batch::paginate(10));
        }

    }

    /**
     * Store a newly created batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        try{
            $data = $request->all();
//            info($data);
            return resOk((new CreateService())->handle($data), 201);
        } catch (Exception $e){
            info($e);
            throw $e;

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
        try{


            info($id);
        } catch (Exception $e){
            info($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Batch $batch)
    {
        try{
            info('in update');
//            return (auth()->user()->_id);
            $data = $request->all();
            return (new UpdateService())->handle($data, $batch);
        } catch (Exception $e){
            info($e);
        }
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
