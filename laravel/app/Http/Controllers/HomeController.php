<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Message;
use App\Reports;
use Cloudder;
use Illuminate\Http\Request;
use Sentinel;


class HomeController extends Controller
{
    public function index(Request $request)
    {

        $ajax = false;
        if ($request->ajax()) {

            $ajax = true;
        }
        return view('home.home')
            ->with('ajax',$ajax);
    }

    public function trending(Request $request)
    {
        $ajax = false;
        if ($request->ajax()) {

            $ajax = true;
        }
        return view('home.trending')
            ->with('ajax',$ajax);
    }

    public function single(Request $request, $id)
    {
        $ajax = false;
        if ($request->ajax()) {

            $ajax = true;
        }
        return view('home.post')
            ->with('id',$id)
            ->with('ajax',$ajax);
    }

    public function report(Request $request)
    {

        $post = $request->post_id;
        $type = $request->gridRadios;
        $comment = $request->comment;

        Reports::create([

            'source'=>$post,
            'type'=>$type,
            'comment'=>$comment

        ]);

        return 'true';

    }

    public function error_404(Request $request){

        $ajax = false;
        if ($request->ajax()) {

            $ajax = true;
        }

        return view('errors.404')
            ->with('ajax',$ajax);

    }
}
