<?php
namespace App\Http\Controllers;

use App\Events\NewPost;
use App\Events\Universal;
use App\Hashtag;
use App\Like;
use App\Notification;
use App\Post;
use App\Post_data;
use App\UnfollowPost;
use Carbon\Carbon;
use Cloudder;
use DB;
use Illuminate\Http\Request;
use Sentinel;
use Weidner\Goutte\GoutteFacade;

class PostController extends Controller
{
    public function show(Request $request, $username = null)
    {

        if ($request->ajax()) {
            if ($username)
                $postAll = Post::where('user_id', $username)->latest()->paginate(10);
            else
                $postAll = Post::
                latest()->paginate(5);

            return $this->getPosts($postAll);
        } else {
            return redirect('/home');

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

    public function show1(Request $request, $p_id)
    {
        if ($request->ajax()) {

            $postAll = Post::where('id', $p_id)->get();

            return $this->getPosts($postAll);

        } else {
            return redirect('/home');
        }
    }

    public function show2(Request $request)
    {

        if ($request->ajax()) {

            $postAll = Post::where('id', '>', $request->id)
                ->orderBy('created_at')
                ->get();

            return $this->getPosts($postAll);

        } else {
            return redirect('/home');
        }
    }
//
//    public function request(Request $request)
//    {
//        $p_id = $request->post_id;
//        if ($request->ajax()) {
//
//            $user = Sentinel::check();
//
//            $postData = array();
//
//            $postAll = Post::where('id', $p_id)
//                ->where('user_id', $user->id)->get();
//
//            foreach ($postAll as $key => $value) {
//
//                if ($value->type_id == 3) {
//                    return response()->json([
//                        'error' => ['You can\'t edit a system generated post !!']
//                    ], 422);
//                }
//
//                $request->session()->push('isEdit', $p_id);
//
//                if ($request->session()->has('post_images')) {
//                    $request->session()->forget('post_images');
//                }
//
//                $post = array();
//                $post["post_id"] = $value->id;
//                $post["user_id"] = $value->user_id;
//                $post["user_name"] = $value->user->name;
//                $post["username"] = '@' . $value->user->username;
//                $post["user_picture"] = Cloudder::show($value->user->profile_picture_small, array());
//                $post["post_text"] = $value->text;
//                $carbon = Carbon::parse($value->created_at)->diffForHumans();
//                $post["post_time"] = $this->date_small($carbon);
//                $post["post_time"] = $this->date_small($carbon);
//                $post["likes"] = $value->like->where('like_type', 1)->count();
//                $post["dislikes"] = $value->like->where('like_type', 0)->count();
//                $post["comments"] = $value->comment->count();
//                $post["isliked"] = $value->like->where('like_type', 1)->where('user_id', $user->id)->count();
//                $post["isdisliked"] = $value->like->where('like_type', 0)->where('user_id', $user->id)->count();
//                $post["iscommented"] = $value->comment->where('user_id', $user->id)->count();
//                $commentAll = $value->comment()->latest()->take(1)->get();
//                $post["post_comment"] = [];
//                $post["post_data"] = [];
//
//                if ($value->type_id >= 2) {
//
//                    $postSources = $value->post_data()->get();
//                    foreach ($postSources as $key1 => $value1) {
//
//                        $postSource = array();
//                        $pieces = explode("-", $value1->source);
//                        $postSource['source'] = Cloudder::show($pieces[0], array());
//                        $request->session()->push('post_images', $value1->source);
//                        $postSource['height'] = $pieces[1];
//                        $postSource['source_id'] = $value1->source;
//                        $postSource['data'] = $value1->data;
//                        $postSource['type'] = $value1->type;
//                        array_push($post["post_data"], $postSource);
//
//                    }
//
//
//                }
//                foreach ($commentAll as $key1 => $value1) {
//                    $post["post_comment"][0]["post_id"] = $value->id;
//                    $post["post_comment"][0]["comment_id"] = $value1->id;
//                    $post["post_comment"][0]["user_id"] = $value1->user_id;
//                    $post["post_comment"][0]["user_name"] = $value1->user->name;
//                    $post["post_comment"][0]["username"] = $value1->user->username;
//                    $post["post_comment"][0]["comment"] = $value1->comment_data;
//                    $carbon1 = Carbon::parse(($value1->created_at))->diffForHumans();
//                    $post["post_comment"][0]["comment_time"] = $this->date_small($carbon1);
//                    $post["post_comment"][0]["user_picture"] = Cloudder::show($value1->user->profile_picture_small, array());
//
//                }
//
//                $post["like_all"] = [];
//
//                $likesAll = $value->like()->where('like_type', '1')->latest()->take(3)->get();
//                $i = 0;
//                foreach ($likesAll as $key1 => $value1) {
//                    $post["like_all"][$i]["post_id"] = $value->id;
//                    $post["like_all"][$i]["user_id"] = $value1->user_id;
//                    $post["like_all"][$i]["user_name"] = $value1->user->name;
//                    $post["like_all"][$i]["username"] = $value1->user->username;
//                    $post["like_all"][$i]["user_picture"] = Cloudder::show($value1->user->profile_picture_small, array());
//                    $i++;
//                }
//
//                array_push($postData, $post);
//
//            }
//            return $postData;
//        } else {
//            return redirect('/home');
//        }
//    }
    public function trending(Request $request)
    {
        if ($request->ajax()) {

            $TrendingPosts = Like::groupBy('post_id')
                ->select('post_id', DB::raw('count(post_id) as total'))
                ->where('created_at','>=', Carbon::now()->subDays(1))
                ->orderby(DB::raw('total'), 'DESC')
                ->get();


            $postID = array();

            foreach ($TrendingPosts as $TrendingPost) {
                array_push($postID, $TrendingPost->post_id);
            }

            $postIDString = implode(",", $postID);

            $postAll = Post::wherein('id', $postID)
                ->orderby(DB::raw("FIELD(id, $postIDString)"))
                ->paginate(5);

            return $this->getPosts($postAll);

        } else {
            return redirect('/home');
        }
    }

    public function create(Request $request)
    {

        $user = Sentinel::check();
        $type_id = 1;
        $post_id = null;

        $postData = $request->postText;
        $match = [];
        if (preg_match("/(http|https)\\:\\/\\/[a-zA-Z0-9\\-\\.]+\\.[a-zA-Z]{2,3}(\\/\\S*)?/", $postData, $match))
            $type_id = 3;

        if ($request->session()->has('post_images'))
            $type_id = 2;

        if ($type_id == 1)
            $this->validate($request, [
                'postText' => 'required',
            ]);


        if ($request->session()->has('isEdit')) {

            $post = $request->session()->get('isEdit');
            foreach ($post as $key => $value) {
                $post_id = $value;
            }

            $post1 = $user->post()->where([
                'id' => $post_id,
            ])->where('type_id', '!=', 3)
                ->update([
                    'text' => $postData,
                    'type_id' => $type_id,
                ]);

            Post_data::where('post_id', $post_id)->delete();
            Hashtag::where('post_id', $post_id)->delete();

        } else {

            $post1 = $user->post()->create([
                'text' => $postData,
                'type_id' => $type_id,
            ]);
            event(new NewPost("post"));

            $post_id = $post1->id;
        }

        $post = array();
        $post["post_data"] = [];

        if ($type_id == 2) {
            $images = $request->session()->get('post_images');
            foreach ($images as $key => $value) {
                Post_data::create([
                    'source' => $value,
                    'type' => $type_id,
                    'data' => null,
                    'post_id' => $post_id,

                ]);

                $postSource = array();
                $pieces = explode("-", $value);
                $postSource['source'] = Cloudder::show($pieces[0], array());
                $postSource['height'] = $pieces[1];
                $postSource['data'] = '';
                $postSource['type'] = '';
                array_push($post["post_data"], $postSource);

            }

        } elseif ($type_id == 3) {

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

                $postSource = array();
                $postSource['source'] = $link;
                $postSource['data'] = '';
                $postSource['type'] = 4;
                array_push($post["post_data"], $postSource);

            }


//            else {
//
////                preg_match("/(http|https|ftp|ftps)\\:\\/\\/[a-zA-Z0-9\\-\\.]+\\.[a-zA-Z]{2,3}(\\/\\S*)?/", $url, $match);
////
////                try {
////                    $crawler = GoutteFacade::setHeader('Accept-Language', 'en-US')
////                        ->setHeader('Accept-Charset', 'utf-8')
////                        ->request('GET', $match[0]);
//////                dump($crawler->filterXPath('//html')->text());
////                    $url = $crawler->filterXPath('//meta[@property="og:url"]')->extract(array('content'));
////                    $image = $crawler->filterXPath('//meta[@property="og:image"]')->extract(array('content'));
////                    $desc = $crawler->filterXPath('//meta[@property="og:description"]')->extract(array('content'));
////
////                    if (count($url)) {
////                        $link = $url[0] . "!*$$$$$*!";
////                    } else {
////                        $link = $match[0] . "!*$$$$$*!";
////                    }
////
////                    if (count($image)) {
////                        $link .= $image[0] . "!*$$$$$*!";
////                    } else {
////
////                        $imgUrl = substr($match[0], 0, strripos($match[0], "/"));
////                        $done = 0;
////                        $img = $crawler->filterXPath('//meta[@itemprop="image"]')->extract(array('content'));
////                        if (count($img)) {
////
////                            if (strpos($img[0], "http") !== false) {
////                                $link .= $img[0] . "!*$$$$$*!";
////                            } else {
////                                $link .= $imgUrl . '/' . $img[0] . " !*$$$$$*!";
////
////                            }
////
////                        } else {
////                            $img = $crawler->filterXPath('//link[@rel="apple-touch-icon"]')->extract(array('href'));
////
////                            if (count($img)) {
////
////                                if (strpos($img[0], "http") !== false) {
////                                    $link .= $img[0] . "!*$$$$$*!";
////                                } else {
////                                    $link .= $imgUrl . '/' . $img[0] . " !*$$$$$*!";
////
////                                }
////
////                            } else {
////                                $images = $crawler->filterXPath('//img')->extract(array('src'));
////
////                                foreach ($images as $image) {
////                                    $needle = "logo";
////                                    if (strpos($image, $needle) !== false) {
////                                        $done = 1;
////                                        if (strpos($image, "http") !== false) {
////                                            $link .= $image . "!*$$$$$*!";
////                                        } else {
////                                            $link .= $imgUrl . '/' . $image . "!*$$$$$*!";
////
////                                        }
////                                        break;
////                                    }
////
////                                }
////
////                                if (!$done) {
////                                    if (count($images)) {
////                                        foreach ($images as $image) {
////                                            if (strlen($image)) {
////                                                if (strpos($image, "http") !== false) {
////                                                    $link .= $image . "!*$$$$$*!";
////                                                } else {
////                                                    $link .= $imgUrl . '/' . $image . "!*$$$$$*!";
////
////                                                }
////                                                break;
////                                            }
////                                        }
////                                    } else
////                                        $link .= "-1" . "!*$$$$$*!";
////
////                                }
////                            }
////
////                        }
////                    }
////
////                    if (count($desc)) {
////                        $link .= $desc[0] . "!*$$$$$*!";
////                    } else {
////                        $mDesc = $crawler->filterXPath('//meta[@name="description"]')->extract(array('content'));
////                        if (count($mDesc)) {
////                            $link .= $mDesc[0] . "!*$$$$$*!";;
////                        } else {
////                            $mDesc = $crawler->filterXPath('//meta[@name="Description"]')->extract(array('content'));
////                            if (count($mDesc)) {
////                                $link .= $mDesc[0] . "!*$$$$$*!";;
////                            } else {
////                                $link .= "" . "!*$$$$$*!";;
////                            }
////                        }
////
////                    }
////
////                    $link .= $crawler->filter('title')->text();
////                }
////                catch(\Exception $e){
////
////                    $link = $match[0];
////                }
//
//                $url = $postData;
////                $match = [];
//                $link = $postData;
//
//                Post_data::create([
//                    'source' => $link,
//                    'type' => 1,
//                    'data' => null,
//                    'post_id' => $post_id,
//                ]);
//
//                $postSource = array();
//                $postSource['source'] = $link;
//                $postSource['data'] = '';
//                $postSource['type'] = 5;
//                array_push($post["post_data"], $postSource);
//
//            }


        }


        preg_match_all("/(#\\w+)/", $postData, $output_array);
        foreach ($output_array[0] as $key => $value) {
            Hashtag::create([
                'tag' => $value,
                'post_id' => $post_id,
            ]);
        }

        $post["id"] = $post_id;
        $post["user_id"] = $user->id;

        if ($type_id == 2)
            $this->addNotification($user, $post_id, 0, 5);
        else {
            if (!$request->session()->has('isEdit'))
                $this->addNotification($user, $post_id, 0, 0);
        }

        return $post;

    }

    public function delete(Request $request)
    {

        $this->validate($request, [
            'post_id' => 'bail|required|integer|exists:posts,id',
        ], [
                'post_id.*' => 'Invalid Post!!',
            ]
        );
        $user = Sentinel::check();
        $post_id = $request->post_id;

        $isdone = Post::where('id', $post_id)
            ->where('user_id', $user->id)
            ->delete();



        if ($isdone) {
            $user->notification()->where([
                'source_id' => $post_id,
                'type' => 0,
            ])->delete();
            return 'true';
        }
        return 'false';

    }

    public function init(Request $request)
    {
        if ($request->session()->has('post_images')) {
            $request->session()->forget('post_images');
        }

        if ($request->session()->has('isEdit')) {
            $request->session()->forget('isEdit');
        }

        if ($request->ajax())
            return 'true';
        else
            return redirect('home');
    }

    public function upload(Request $request)
    {

        if ($request->session()->has('post_images')) {
            return $this->ajaxError('We currently accept only one image per post !');
        } else {
            $this->validate($request, [
                'post_picture' => 'required | image',
            ]);

            try {

                Cloudder::upload($request->file('post_picture'), null,
                    array(
                        "format" => "jpg",
                        "width" => 500, "height" => 500, "crop" => "limit",
                    ));

            } catch (Exception $e) {
                return $this->ajaxError('Invalid Image format !');
            }

            $image2 = Cloudder::getResult();

            $image = $image2["public_id"];
            $image1 = $image2["height"];
            $image01 = $image2["width"];

            $image1 = $image1 / $image01;

            $request->session()->push('post_images', $image . '-' . $image1);
            $uploadData["image"] = Cloudder::show($image, array());
            $uploadData["image_id"] = $image . '-' . $image1;
            return $uploadData;
        }
    }

    public function upload_remove(Request $request)
    {
        $this->validate($request, [
            'image_id' => 'required',
        ]);


        if ($request->session()->has('post_images')) {

            $post_image_id = $request->image_id;

            $images = $request->session()->get('post_images');
            foreach ($images as $key => $value) {
                if ($post_image_id == $value) {
                    $request->session()->pull('post_images', $value);
                    Cloudder::destroyImage($value, array());

                }
            }

        } else {

            return $this->ajaxError('Nothing to remove!');

        }
    }

    public function like(Request $request)
    {
        $user = Sentinel::check();
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
                $this->addNotification($user, $post_id, 0, 1);
                event(new Universal("like"));
            }

        } else {
            if ($like[0]["like_type"] == $like_type) {

                $Q_like->delete();

                if ($like_type == 1)
                    $this->removeNotification($user, $post_id, 0, 1);

            } else {

                $Q_like->update(['like_type' => $like_type]);

                if ($like_type == 1) {
                    UnfollowPost::where([
                        'post_id' => $post_id,
                        'user_id' => $user_id,
                    ])->delete();
                    $this->addNotification($user, $post_id, 0, 1);
                    event(new Universal("like"));
                }

                if ($like_type == 0)
                    $this->removeNotification($user, $post_id, 0, 1);
            }
        }

        return $like_type;
    }

    public function like_all(Request $request, $id)
    {

        $like_data = Like::where('post_id', $id)->where('like_type', '1')->latest()->get();

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

    public function Unfollow_Post(Request $request)
    {
        $this->validate($request, [
            'post_id' => 'bail|required|integer|exists:posts,id',
        ], [
                'post_id.*' => 'Invalid Post!!',
            ]
        );

        $user = Sentinel::check();

        UnfollowPost::create([
            'post_id' => $request->post_id,
            'user_id' => $user->id,
        ]);

    }

    public function addNotification($user_id, $source_id, $type, $activity_type)
    {

        $user_id->notification()->create([
            'source_id' => $source_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ]);

    }

    public function removeNotification($user_id, $source_id, $type, $activity_type)
    {

        $user_id->notification()->where([
            'source_id' => $source_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ])->delete();
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

