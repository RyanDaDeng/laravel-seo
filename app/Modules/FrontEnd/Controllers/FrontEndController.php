<?php

namespace App\Modules\FrontEnd\Controllers;


use App\Http\Controllers\Controller;

class FrontEndController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui::index');
    }

    public function apiManagement()
    {
        return view('ui::api-management');
    }

    public function setting()
    {
        return view('ui::setting');
    }
}
