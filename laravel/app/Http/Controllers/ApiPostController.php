<?php

namespace App\Http\Controllers;

use App\Events\NewPost;
use App\Post_data;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use App\Like;
use App\Notification;
use App\Post;
use App\UnfollowPost;
use DB;


class ApiPostController extends Controller
{
    public function posts(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {


            $postAll = Post::latest()->paginate(5);

            return $this->getPosts($postAll, $user);
        }

        return "-1";
    }

    public function trend(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {


            $TrendingPosts = Like::groupBy('post_id')
                ->select('post_id', DB::raw('count(post_id) as total'))
                ->where('created_at', '>=', Carbon::now()->subDays(3))
//                ->where('post_id', '!=', "1501")
                ->orderby(DB::raw('total'), 'DESC')
                ->get();


            $postID = array();


//                            array_push($postID, "1501");

            foreach ($TrendingPosts as $TrendingPost) {
                array_push($postID, $TrendingPost->post_id);
            }

            $postIDString = implode(",", $postID);

            $postAll = Post::wherein('id', $postID)
                ->orderby(DB::raw("FIELD(id, $postIDString)"))
                ->paginate(5);

            return $this->getPosts($postAll, $user);
        }

        return "-1";
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


    public function postText(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $type_id = 1;
            $post_id = null;

            $postData = $request->text;


            $match = [];
            if (preg_match("/(http|https)\\:\\/\\/[a-zA-Z0-9\\-\\.]+\\.[a-zA-Z]{2,3}(\\/\\S*)?/", $postData, $match))
                $type_id = 3;


            $post1 = Post::create([
                'text' => $postData,
                'type_id' => $type_id,
                'user_id' => $userID
            ]);

            $post_id = $post1->id;




//            preg_match_all("/(#\\w+)/", $postData, $output_array);
//            foreach ($output_array[0] as $key => $value) {
//                Hashtag::create([
//                    'tag' => $value,
//                    'post_id' => $post_id,
//                ]);
//            }


            if ($type_id == 3) {

                $url = $postData;
                $match = [];
                $link = null;
                if (preg_match("/(youtube.com|youtu.be)\\/(watch)?(\\?v=)?(\\S+)?/", $url, $match)) {
                    $link = "<iframe style='width:100%;' height='250' src=\"https://www.youtube.com/embed/$match[4]?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>";
                    Post_data::create([
                        'source' => $link,
                        'type' => 4,
                        'data' => null,
                        'post_id' => $post_id,
                    ]);

                }


            }
                event(new NewPost("post"));
                $post["id"] = $post_id;
//                $post["user_id"] = $user->id;
                $post["text"] = $postData;
            $this->addNotification($userID, $post_id, 0, 0);

            return $post;


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


    public function postImage(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $type_id = 2;
            $post_id = null;
            $postData = $request->metadata;

            $post1 = Post::create([
                'text' => $postData,
                'type_id' => $type_id,
                'user_id' => $userID
            ]);
            event(new NewPost("post"));


            $post_id = $post1->id;

            try {

                Cloudder::upload($request->file('uploadfile'), null,
                    array(
                        "format" => "jpg",
                        "width" => 500, "height" => 500, "crop" => "limit",
                    ));

            } catch (Exception $e) {
                return 'Invalid Image format !';
            }

            $image2 = Cloudder::getResult();
            $image = $image2["public_id"];
            $image1 = $image2["height"];
            $image01 = $image2["width"];
            $image1 = $image1 / $image01;
            $value = $image . '-' . $image1;

            Post_data::create([
                'source' => $value,
                'type' => $type_id,
                'data' => null,
                'post_id' => $post_id,

            ]);

            $pieces = explode("-", $value);
            $post['source'] = Cloudder::show($pieces[0], array());
            $post['height'] = $pieces[1];
            $post["id"] = $post_id;
            $post["user_id"] = $user->id;
            $post["text"] = $postData;

            $this->addNotification($userID, $post_id, 0, 0);

            return $post;

        }
    }

    public function pools(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $result = array();

            $psit = array();
            $psit["name"] = "PSIT";
            $psit["image"] = "http://res.cloudinary.com/fabits-in/image/upload/v1492351817/ec2ef755-ddc8-4c47-ab10-3f77351bc532_hl5mc3_ka8xjt.jpg";
            $psit["search_id"] = "164";

            $psitCOE = array();
            $psitCOE["name"] = "PSIT-COE";
            $psitCOE["image"] = "http://res.cloudinary.com/fabits-in/image/upload/v1492353532/psitcoe_z2ri3z.png";
            $psitCOE["search_id"] = "348";

            $psitCHE = array();
            $psitCHE["name"] = "PSIT-CHE";
            $psitCHE["image"] = "http://res.cloudinary.com/fabits-in/image/upload/v1492353532/psitche_yjsbzm.png";
            $psitCHE["search_id"] = "1000";

            $naraina = array();
            $naraina["name"] = "NARAINA";
            $naraina["image"] = "http://res.cloudinary.com/fabits-in/image/upload/v1492353839/naraina_mksf6y.png";
            $naraina["search_id"] = "287";

            array_push($result, $psit);
            array_push($result, $psitCOE);
            array_push($result, $psitCHE);
            array_push($result, $naraina);

            return $result;

        }
    }


    public function deletePost(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $post_id = $request->post_id;

            $isdone = Post::where('id', $post_id)
                ->where('user_id', $userID)
                ->delete();

            return $isdone;
        }
    }


    public function unFollowPost(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $post_id = $request->post_id;

            UnfollowPost::create([
                'post_id' => $post_id,
                'user_id' => $userID,
            ]);

        }
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
