<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\Universal;
use App\UnfollowPost;
use Carbon\Carbon;
use Cloudder;
use Illuminate\Http\Request;
use Sentinel;

class CommentController extends Controller
{


    public function create(Request $request)
    {
        $this->validate($request, [
            'postid' => 'bail|required|integer|exists:posts,id',
            'comment' => 'required',
        ],
            [
                'comment.*' => 'Comment can\'t be blank!',
                'postid.*' => 'Invalid request!!',

            ]);

        $user = Sentinel::check();
        $userID = $user->id;

        $Use_comment =  $request->comment;
        $postId = $request->postid;


        $comment = Comment::create([
            'comment_data' => $Use_comment,
            'post_id' => $postId,
            'user_id' => $userID,
        ]);

        UnfollowPost::where([
            'post_id' =>$postId,
            'user_id' =>$userID,
        ])->delete();

        $this->addNotification($user, $postId, 0, 2);
        event(new Universal("comment"));



        $commentData = [];
        $commentData["comment"] = $Use_comment;
        $commentData["post_id"] = $postId;
        $commentData["user_id"] = $userID;
        $commentData["id"] = $comment->id;


        return $commentData;
    }

    public function show(Request $request)
    {

        $this->validate($request, [
            'post_id' => 'bail|required|integer|exists:posts,id',
            'load' => 'bail|required|integer',
        ], [
            'post_id.*' => 'Invalid request!!',
            'load.*' => 'Invalid request!!',
        ]);

        $load = $request->load;
        $post_id = $request->post_id;

        $offset = ($load * 4)+1;

        $commentData = array();
        $comment = Comment::where('post_id', $post_id)
            ->orderBy('created_at', 'desc')
            ->offset($offset)->limit(4)->get();

        foreach ($comment as $key => $value) {
            $post = array();
            $post["comment_id"] = $value->id;
            $post["post_id"] = $post_id;
            $post["user_id"] = $value->user_id;
            $post["user_name"] = $value->user->name;
            $post["username"] = $value->user->username;
            $post["comment"] = $value->comment_data;
            $carbon1 = Carbon::parse(($value->created_at))->diffForHumans();
            $post["comment_time"] = $this->datesmall($carbon1);
            $post["user_picture"] = Cloudder::show($value->user->profile_picture_small, array());
            array_push($commentData, $post);

        }
        $commentData = array_reverse($commentData);
        return $commentData;


    }

    public function addNotification($userid, $sourceid, $type, $activitytype)
    {
        $userid->notification()->create([
            'source_id' => $sourceid,
            'type' => $type,
            'activity_type' => $activitytype,
        ]);
    }

    public function  delete(Request $request){

        $this->validate($request, [
            'post_id' => 'bail|required|integer|exists:posts,id',
            'id' => 'bail|required|integer|exists:comments,id',
        ],
            [
                'id.*' => 'Invalid request!!',
                'post_id.*' => 'Invalid request!!',

            ]);


        $user = Sentinel::check();
        $post_id = $request->post_id;
        $id = $request->id;

        $isDone =  Comment::where('post_id', $post_id)
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->delete();
        if($isDone)
            return 'true';
        else
            return 'false';

    }

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

    }

    public function datesmall($Date)
    {
        $Date = str_replace("second", "s", $Date);
        $Date = str_replace("ss", "s", $Date);
        $Date = str_replace("minute", "m", $Date);
        $Date = str_replace("ms", "m", $Date);
        $Date = str_replace("hour", "h", $Date);
        $Date = str_replace("hs", "h", $Date);
        $Date = str_replace("day", "d", $Date);
        $Date = str_replace("ds", "d", $Date);
        $Date = str_replace("week", "w", $Date);
        $Date = str_replace("ws", "w", $Date);
        $Date = str_replace("year", "y", $Date);
        $Date = str_replace("ys", "y", $Date);
        return $Date;
    }
}
