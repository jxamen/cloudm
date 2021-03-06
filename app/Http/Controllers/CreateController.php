<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CreateController extends Controller
{

    public $lastStep = "2";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($step)
    {
        if(Auth::user()->PorC == "P")
        {
            return redirect()->action('MainController@index');
        }
        return view('p_add' . $step);
//        return phpinfo();
    }

    public function postCreate(Request $request, $step)
    {
        switch ($step) {
            case 1:
                Session::put('title', $request->input('title'));
                if(!Session::has('title'))
                    abort(503);
                break;

            case 2:
                Session::put('category', $request->input('category'));
                if(!Session::has('category'))
                    abort(503);
                break;

        }

        if ($step == $this->lastStep) {
            $input = new Project();
            $input->title = Session::pull('title');
            $input->category = Session::pull('category');
            $input->save();

            return redirect()->action('CreateController@complete');

        }
        return redirect()->action('CreateController@index', ['step' => $step+1]);
    }


    public function complete()
    {
        return view('p_add3');
    }
}
