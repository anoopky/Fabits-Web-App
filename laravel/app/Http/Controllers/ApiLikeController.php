<?php

namespace App\Http\Controllers;

use App\Like;
use App\Notification;
use App\oAuth;
use App\UnfollowPost;
use Cloudder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiLikeController extends Controller
{

    public function like(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $user->id = $user->user_id;
            $user_id = $user->id;
            $this->validate($request, [
                'post_id' => 'bail|required|integer|exists:posts,id',
                'like_type' => 'bail|required|integer|between:0,1',
            ], [
                    'post_id.*' => 'Invalid Like!!',
                    'like_type.*' => 'Invalid Like!!',
                ]
            );

            $post_id = $request->post_id;
            $like_type = $request->like_type;
            $Q_like = Like::where('post_id', $post_id)
                ->where('user_id', $user_id);

            $like = $Q_like->get()->toArray();

            if (count($like) == 0) {

                Like::create([
                    'like_type' => $like_type,
                    'post_id' => $post_id,
                    'user_id' => $user_id,
                ]);

                if ($like_type == 1) {

                    UnfollowPost::where([
                        'post_id' => $post_id,
                        'user_id' => $user_id,
                    ])->delete();
                    $this->addNotification($user_id, $post_id, 0, 1);

                }

            } else {
                if ($like[0]["like_type"] == $like_type) {
                    $Q_like->delete();
                    if ($like_type == 1)
                        $this->removeNotification($user_id, $post_id, 0, 1);
                } else {

                    $Q_like->update(['like_type' => $like_type]);

                    if ($like_type == 1) {
                        UnfollowPost::where([
                            'post_id' => $post_id,
                            'user_id' => $user_id,
                        ])->delete();

                        $this->addNotification($user_id, $post_id, 0, 1);
                    }

                    if ($like_type == 0)
                        $this->removeNotification($user_id, $post_id, 0, 1);

                }
            }

            return $like_type;
        }
    }

    public function likes(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $postId = $request->source_id;

            $like_data = Like::where('post_id', $postId)->where('like_type', '1')->latest()->paginate(10);

            $likeData = array();

            foreach ($like_data as $key1 => $value1) {
                $like = array();
                $like["user_id"] = $value1->user_id;
                $like["user_name"] = $value1->user->name;
                $like["username"] = $value1->user->username;
                $like["user_picture"] = Cloudder::show($value1->user->profile_picture_small, array());
                $carbon1 = Carbon::parse(($value1->created_at))->diffForHumans();
                $like["time"] = $this->date_small($carbon1);
                array_push($likeData, $like);
            }

            return $likeData;
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

    public function date_small($Date)
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
