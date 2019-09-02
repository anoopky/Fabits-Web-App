<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Notification;
use App\oAuth;
use Cloudder;
use Carbon\Carbon;
use App\UnfollowPost;
use Illuminate\Http\Request;

class ApiCommentController extends Controller
{
    public function comments(Request $request, $token, $postId)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $commentData = array();
            $comment = Comment::where('post_id', $postId)->latest()->paginate(10);

            foreach ($comment as $key => $value) {
                $post = array();
                $post["comment_id"] = $value->id;
                $post["post_id"] = $postId;
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
    }

    public function comment(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $Use_comment = $request->comment;
            $postId = $request->post_id;


            $comment = Comment::create([
                'comment_data' => $Use_comment,
                'post_id' => $postId,
                'user_id' => $userID,
            ]);

            UnfollowPost::where([
                'post_id' => $postId,
                'user_id' => $userID,
            ])->delete();


            $commentData = [];
            $commentData["id"] = $comment->id;
            $carbon1 = Carbon::parse(($comment->created_at))->diffForHumans();
            $commentData["comment_time"] = $this->datesmall($carbon1);
            $this->addNotification($userID, $postId, 0, 2);
            return $commentData;
        }
    }

    public function addNotification($user_id, $source_id, $type, $activity_type)
    {

        Notification::create([
            'user_id' => $user_id,
            'source_id' => $source_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ]);

    }

    public function removeNotification($user_id, $source_id, $type, $activity_type)
    {

        Notification::where([
            'user_id' => $user_id,
            'source_id' => $source_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ])->delete();
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


    public function deleteComment(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $post_id = $request->post_id;
            $id = $request->id;

            $isDone = Comment::where('post_id', $post_id)
                ->where('id', $id)
                ->where('user_id', $userID)
                ->delete();

            return $isDone;
        }
    }


}
