<?php

namespace App\Http\Controllers;

use App\Http\Requests\Test;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @param Test $request
     * @return \Illuminate\Http\Response
     */
    public function index(Test $request)
    {
        return resOk(['name' => $request->get("name")]);
    }
}
