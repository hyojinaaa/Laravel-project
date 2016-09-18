<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AboutUsController extends Controller
{
    public function index(){
    	return view('about-us.index');
    }
}
