<?php

namespace App\Http\Controllers;

use App\Facematch;
use App\Follow;
use App\Hashtag;
use App\Notification;
use App\Post;
use App\Profileview;
use App\UnfollowPost;
use Carbon\Carbon;
use Cloudder;
use DB;
use Illuminate\Http\Request;
use Sentinel;


class SearchController extends Controller
{

    public function index(Request $request, $search, $ajaxR = false)
    {
        if (substr($search, 0, 1) === "#") {

            if ($request->ajax()) {

                $postAll = Hashtag::groupBy('tag')
                    ->having('tag', 'like', $search . '%')
                    ->select('tag', DB::raw('count(tag) as total'))
                    ->get();

                return $postAll;

            }
        } elseif (substr($search, 0, 2) === "!#") {
            if ($request->ajax()) {

                $search = substr($search, 1, strlen($search));

                $postAll = Post::
                leftJoin('hashtags', 'posts.id', '=', 'hashtags.post_id')
                    ->where('hashtags.tag', 'like', $search . '%')
                    ->select(['posts.*'])
                    ->orderBy('posts.created_at', 'DESC')->paginate(10);

                return $this->getPosts($postAll);
            }
        } else {
            $user = Sentinel::check();
            $searchList = array();
            if ($ajaxR == 'true') {
                $searches = DB::table('users')
                    ->where('name', 'like', '%' . $search . '%')
                    ->orwhere('username', 'like', '%' . $search . '%')
                    ->orwhere('college_id', 'like', '%' . $search . '%')
                    ->orwhere('university_id', 'like', '%' . $search . '%')
                    ->orwhere('phone', 'like',$search . '%')
                    ->offset(0)
                    ->limit(3)
                    ->get();
            }
            else{

                $searches = DB::table('users')
                    ->where('name', 'like', '%' . $search . '%')
                    ->orwhere('username', 'like', '%' . $search . '%')
                    ->orwhere('college_id', 'like', '%' . $search . '%')
                    ->orwhere('university_id', 'like', '%' . $search . '%')
                    ->orwhere('phone', 'like', $search . '%')
                    ->get();

            }
            foreach ($searches as $search) {

                $search_user = array();
                $search_user["name"] = $search->name;
                $search_user["id"] = $search->id;
                if ($search->id == $user->id)
                    continue;
                $search_user["username"] = $search->username;
                $search_user["intro"] = $search->intro;
                $search_user["relationship"] = $search->relationship_id;
                $search_user["image"] = Cloudder::show($search->profile_picture_big, array());
                $followers = Follow::where('user_id2', $search->id)
                    ->select('id')->count();
                $isfollow = Follow::where('user_id2', $search->id)
                    ->where('user_id1', $user->id)
                    ->select('id')->count();
                $following = Follow::where('user_id1', $search->id)
                    ->select('id')->count();
                $profileviews = Profileview::where('user_id2', $search->id)
                    ->select('id')->count();
                $facematch = 100;
                $postcount = Post::where('user_id', $search->id)->select('id')
                    ->count();

                $facematchs = Facematch::where('user_id1', $search->id)
                    ->orwhere('user_id2', $search->id)
                    ->select('id', 'user_ids')->get();

                $total = 0;
                $select = 0;
                foreach ($facematchs as $facematch) {

                    if ($facematch->user_ids == $search->id)
                        $select++;
                    $total++;
                }

                $search_user["followers"] = $followers;
                $search_user["posts"] = $postcount;
                $search_user["following"] = $following;
                $search_user["profileviews"] = $profileviews;
                $search_user["isfollow"] = $isfollow;
                if ($total == 0)
                    $search_user["facematch"] = "N/10";
                else
                    $search_user["facematch"] = round(($select / $total) * 10, 1) . "/10";

                array_push($searchList, $search_user);
            }

            $ajax = false;
            if ($request->ajax()) {

                $ajax = true;
            }

            if ($ajaxR == 'true') {
                return $searchList;
            } else {
                return view('home.search')
                    ->with('ajax', $ajax)
                    ->with('searchs', $searchList);
            }
        }
    }

    public function getPosts($postAll)
    {
        $user = Sentinel::check();
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
            $post["isliked"] = $value->like->where('like_type', 1)->where('user_id', $user->id)->count();
            $post["isdisliked"] = $value->like->where('like_type', 0)->where('user_id', $user->id)->count();
            $post["iscommented"] = $value->comment->where('user_id', $user->id)->count();
            $post["isfollow"] = UnfollowPost::where('user_id', $user->id)
                ->where('post_id', $value->id)->count() ? 0 : Notification::select(['source_id', 'type', 'activity_type', 'created_at'])
                ->where('user_id', $user->id)
                ->where('source_id', $value->id)
                ->where('type', 0)
                ->groupby(['source_id', 'type'])
                ->orderby('created_at', 'desc')
                ->count();

            $commentAll = $value->comment()->latest()->take(1)->get();
            $post["post_comment"] = [];
            $post["post_data"] = [];

            if ($value->type_id >= 2) {

                $postSources = $value->post_data()->get();
                foreach ($postSources as $key1 => $value1) {

                    $postSource = array();
                    if ($value1->type == 4) {

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

            $likesAll = $value->like()->where('like_type', '1')->latest()->take(3)->get();
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

    public function recommendedPeople(Request $request)
    {

        $user = Sentinel::check();
        $searchList = array();
        $searches = DB::table('users')
            ->where('id', '!=', $user->id)
            ->where('college_name_id', $user->college_name_id)
            ->where('branch_id', $user->branch_id)
            ->orderBy(DB::raw('RAND()'))
            ->offset(0)
            ->limit(10)
            ->get();

        foreach ($searches as $search) {

            $search_user = array();
            $search_user["name"] = $search->name;
            $search_user["id"] = $search->id;
            if ($search->id == $user->id)
                continue;
            $search_user["username"] = $search->username;
            $search_user["intro"] = $search->intro;
//            $search_user["relationship"] = $search->relationship_id;
            $search_user["image"] = Cloudder::show($search->profile_picture_big, array());
            $followers = Follow::where('user_id2', $search->id)
                ->select('id')->count();
            $isfollow = Follow::where('user_id2', $search->id)
                ->where('user_id1', $user->id)
                ->select('id')->count();

            if ($isfollow > 0)
                continue;
            $following = Follow::where('user_id1', $search->id)
                ->select('id')->count();
            $profileviews = Profileview::where('user_id2', $search->id)
                ->select('id')->count();
//            $postcount = Post::where('user_id', $search->id)->select('id')
//                ->count();

//            $facematchs = Facematch::where('user_id1', $search->id)
//                ->orwhere('user_id2', $search->id)
//                ->select('id', 'user_ids')->get();

            $total = 0;
            $select = 0;
//            foreach ($facematchs as $facematch) {
//
//                if ($facematch->user_ids == $search->id)
//                    $select++;
//                $total++;
//            }

            $search_user["followers"] = $followers;
//            $search_user["posts"] = $postcount;
            $search_user["following"] = $following;
            $search_user["profileviews"] = $profileviews;
            $search_user["isfollow"] = $isfollow;
//            if ($total == 0)
//                $search_user["facematch"] = "N/10";
//            else
//                $search_user["facematch"] = round(($select / $total) * 10, 1) . "/10";

            array_push($searchList, $search_user);
        }


        if ($request->ajax()) {


            return $searchList;

        }

    }

    public function topPeople(Request $request)
    {
        $top = Follow::groupBy('user_id2')
            ->select('user_id2', DB::raw('count(user_id2) as total'))
            ->where('created_at','>=', Carbon::now()->subDays(2))
            ->orderby(DB::raw('total'), 'DESC')
            ->get();

        $searchList = array();

        $topIDs = array();
        array_push($topIDs, '5198');

        foreach ($top as $topID) {
            array_push($topIDs, $topID->user_id2);
        }
        $topIDString = implode(",", $topIDs);



        $searches = DB::table('users')
            ->wherein('id', $topIDs)
            ->where('university_id','!=','1416413057')
            ->orderby(DB::raw("FIELD(id, $topIDString)"))
            ->paginate(6);

        foreach ($searches as $search) {

            $search_user = array();
            $search_user["name"] = $search->name;
            $search_user["id"] = $search->id;
            $search_user["username"] = $search->username;
//            $search_user["intro"] = $search->intro;
//            $search_user["relationship"] = $search->relationship_id;
            $search_user["image"] = Cloudder::show($search->profile_picture_big, array());
            $followers = Follow::where('user_id2', $search->id)
                ->select('id')->count();

            $search_user["followers"] = $followers;

            array_push($searchList, $search_user);
        }


        if ($request->ajax()) {


            return $searchList;

        }

    }

    public function topHashtag(Request $request)
    {

        if ($request->ajax()) {

            $postAll = Hashtag::groupBy('tag')
                ->select('tag', DB::raw('count(tag) as total'))
                ->orderBy(DB::raw('total'),'DESC')
                ->offset(0)
                ->limit(5)
                ->get();

            return $postAll;


        }

    }

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

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
