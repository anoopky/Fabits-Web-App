<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use App\Notification;
use App\Post;
use App\UnfollowPost;
use DB;

class ApiSearchController extends Controller
{
    public function search(Request $request, $token, $search, $type)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;


            if ($type == "user") {

                $search = str_replace("+", " ", $search);

                $searchList = array();
                $searches = DB::table('users')
                    ->where('name', 'like', '%' . $search . '%')
                    ->where('id', '!=', $userID)
                    ->orwhere('username', 'like', '%' . $search . '%')
                    ->orwhere('college_id', 'like', '%' . $search . '%')
                    ->orwhere('university_id', 'like', '%' . $search . '%')
                    ->orwhere('phone', 'like', $search . '%')
                    ->paginate(10);

                foreach ($searches as $search) {

                    $search_user = array();
                    $search_user["name"] = $search->name;
                    $search_user["id"] = $search->id;
                    $search_user["username"] = $search->username;
                    $search_user["intro"] = $search->intro;
                    $search_user["image"] = Cloudder::show($search->profile_picture_small, array());
                    array_push($searchList, $search_user);
                }
                return $searchList;

            } else if ($type == "post") {

                $postAll = Post::where('id', $search)->get();

                return $this->getPosts($postAll, $user);

            } else if ($type == "pool") {

                $postAll = Post::
                join('users', 'posts.user_id', '=', 'users.id')
                    ->select('*','posts.created_at as created_at', 'posts.id as id')
                    ->where('college_name_id', $search)
                    ->orderby('posts.id', 'DESC')
                    ->paginate(5);
                return $this->getPosts($postAll, $user);
            } else if ($type == "profile") {


                $postAll = Post::where('user_id', $search)->latest()->paginate(5);


                return $this->getPosts($postAll, $user);

            }

        }
    }

    public function getPosts($postAll, $user)
    {

        $postData = array();

        foreach ($postAll as $key => $value) {
            $post = array();
            $post["post_id"] = $value->id;
            $post["user_id"] = $value->user_id;
            $post["user_name"] = $value->user->name;
            $post["username"] = '@' . $value->user->username;
            $post["user_picture"] = Cloudder::show($value->user->profile_picture_small, array());
            $post["post_text"] = $value->text;
            $carbon = Carbon::parse($value->created_at)->diffForHumans();
            $post["post_time"] = $this->date_small($carbon);
            $post["post_time"] = $this->date_small($carbon);
            $post["likes"] = $value->like->where('like_type', 1)->count();
            $post["dislikes"] = $value->like->where('like_type', 0)->count();
            $post["comments"] = $value->comment->count();
            $post["isliked"] = $value->like->where('like_type', 1)->where('user_id', $user->user_id)->count();
            $post["isdisliked"] = $value->like->where('like_type', 0)->where('user_id', $user->user_id)->count();
            $post["iscommented"] = $value->comment->where('user_id', $user->user_id)->count();

            $post["isfollow"] = UnfollowPost::where('user_id', $user->user_id)
                ->where('post_id', $value->id)->count() ? 0 : Notification::select(['source_id', 'type', 'activity_type', 'created_at'])
                ->where('user_id', $user->user_id)
                ->where('source_id', $value->id)
                ->where('type', 0)
                ->groupby(['source_id', 'type'])
                ->orderby('created_at', 'desc')
                ->count();

            $commentAll = $value->comment()->latest()->offset(0)->limit(1)->get();
            $post["post_comment"] = [];
            $post["post_data"] = [];

            if ($value->type_id >= 2) {

                $postSources = $value->post_data()->get();
                foreach ($postSources as $key1 => $value1) {

                    $postSource = array();
                    if ($value1->type == 4 || $value1->type == 5) {

                        $postSource['source'] = $value1->source;

                    } else {
                        $pieces = explode("-", $value1->source);
                        $postSource['source'] = Cloudder::show($pieces[0], array());
                        $postSource['height'] = $pieces[1];

                    }

                    $postSource['data'] = $value1->data;
                    $postSource['type'] = $value1->type;
                    array_push($post["post_data"], $postSource);

                }


            }
            foreach ($commentAll as $key1 => $value1) {
                $post["post_comment"][0]["post_id"] = $value->id;
                $post["post_comment"][0]["comment_id"] = $value1->id;
                $post["post_comment"][0]["user_id"] = $value1->user_id;
                $post["post_comment"][0]["user_name"] = $value1->user->name;
                $post["post_comment"][0]["username"] = $value1->user->username;
                $post["post_comment"][0]["comment"] = $value1->comment_data;
                $carbon1 = Carbon::parse(($value1->created_at))->diffForHumans();
                $post["post_comment"][0]["comment_time"] = $this->date_small($carbon1);
                $post["post_comment"][0]["user_picture"] = Cloudder::show($value1->user->profile_picture_small, array());

            }

            $post["like_all"] = [];

            $likesAll = $value->like()->where('like_type', '1')->latest()->offset(0)->limit(3)->get();
            $i = 0;
            foreach ($likesAll as $key1 => $value1) {
                $post["like_all"][$i]["post_id"] = $value->id;
                $post["like_all"][$i]["user_id"] = $value1->user_id;
                $post["like_all"][$i]["user_name"] = $value1->user->name;
                $post["like_all"][$i]["username"] = $value1->user->username;
                $post["like_all"][$i]["user_picture"] = Cloudder::show($value1->user->profile_picture_small, array());
                $i++;
            }

            array_push($postData, $post);

        }

        return $postData;
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
