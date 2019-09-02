<?php

namespace App\Http\Controllers;


use App\Block;
use App\Branch;
use App\College_name;
use App\Events\NewPost;
use App\Events\Notify;
use App\Facematch;
use App\Follow;
use App\Post;
use App\Post_data;
use App\Privacy;
use App\Profileview;
use Carbon\Carbon;
use Cloudder;
use Illuminate\Http\Request;
use Sentinel;

class ProfileController extends Controller
{

    public function index(Request $request, $username)
    {

        $userprofile = Sentinel::findUserByCredentials(array(
            'username' => $username,
        ));

        if (count($userprofile)) {
            $profiledata = [];
            $user = Sentinel::check();
            $followers = Follow::where('user_id2', $userprofile->id)
                ->select('id')->count();
            $isfollow = Follow::where('user_id2', $userprofile->id)
                ->where('user_id1', $user->id)
                ->select('id')->count();
            $following = Follow::where('user_id1', $userprofile->id)
                ->select('id')->count();
            $profileviews = Profileview::where('user_id2', $userprofile->id)
                ->select('id')->count();

            $profileviews_user = Profileview::where('user_id2', $userprofile->id)
                ->where('user_id1', $user->id)
                ->latest()->first();

            $Privacy = Privacy::where('user_id', $userprofile->id)->first();

            if ($profileviews_user) {
                $carbon1 = Carbon::parse(($profileviews_user->created_at))->diffInSeconds();

                if ($carbon1 > 60) {
                    if ($user->id != $userprofile->id) {

                        Profileview::create([
                            'user_id1' => $user->id,
                            'user_id2' => $userprofile->id
                        ]);

                        $this->addNotification($userprofile, $user->id, 3, 0);
                        event(new Notify($userprofile->id));

                    }
                }

            } else {
                if ($user->id != $userprofile->id) {
                    Profileview::create([
                        'user_id1' => $user->id,
                        'user_id2' => $userprofile->id
                    ]);
                    $this->addNotification($userprofile, $user->id, 3, 0);
                    event(new Notify($userprofile->id));


                }
            }
            $postcount = Post::where('user_id', $userprofile->id)->select('id')
                ->count();

            $isblock = Block::where('user_id2', $userprofile->id)
                ->where('user_id1', $user->id)
                ->select('id')->count();

            $facematchs = Facematch::where('user_id1', $userprofile->id)
                ->orwhere('user_id2', $userprofile->id)
                ->whereNotNull('user_ids')
                ->select('id', 'user_ids')
//                ->where('user_ids')
                ->get();

            $total = 0;
            $select = 0;
            foreach ($facematchs as $facematch) {

                if ($facematch->user_ids == $userprofile->id)
                    $select++;
                $total++;
            }

            $yearCurr = Carbon::now()->year - "$userprofile->college_year";


            $collegeName = College_name::where('id', $userprofile->college_name_id)->select('name')->first()->name;

            $branchName = Branch::where('id', $userprofile->branch_id)->select('name')->first()->name;

            $profiledata["college"] = $collegeName;
            $profiledata["branch"] = $branchName;
            if($yearCurr == 0)
                $profiledata["year"] = "";
                else
            $profiledata["year"] = "0" . $yearCurr;
            $profiledata["followers"] = $followers;
            $profiledata["following"] = $following;
            $profiledata["profile_views"] = $profileviews;
            $profiledata["posts"] = $postcount;
            $profiledata["isfollow"] = $isfollow;
            $profiledata["isblock"] = $isblock;

            if ($total == 0)
                $profiledata["faceMatch_Rating"] = "N/10";
            else
                $profiledata["faceMatch_Rating"] = round(($select / $total) * 10, 1) . "/10";

            $ajax = false;
            if ($request->ajax()) {

                $ajax = true;
            }

            if($userprofile["birth_day"])
            $dob = $userprofile["birth_day"] . " " . $this->monthname($userprofile["birth_month"]);
else

    $dob = '-';

            return view('home.profile')
                ->with('profiledata', $profiledata)
                ->with('ajax', $ajax)
                ->with('dob', $dob)
                ->with('userprofile', $userprofile)
                ->with('Privacy', $Privacy);
        } else {
            $ajax = false;
            if ($request->ajax()) {

                $ajax = true;
            }

            return view('errors.404')
                ->with('ajax', $ajax);

        }
    }

    public function monthname($month)
    {

        switch ($month) {

            case 1:
                return "Jan";
            case 2:
                return "Feb";
            case 3:
                return "Mar";
            case 4:
                return "Apr";
            case 5:
                return "May";
            case 6:
                return "June";
            case 7:
                return "July";
            case 8:
                return "Aug";
            case 9:
                return "Sep";
            case 10:
                return "Oct";
            case 11:
                return "Nov";
            case 12:
                return "Dec";


        }


    }

    public function profileupload(Request $request)
    {
        $this->validate($request, [
            'profile_picture' => 'required | image',
        ]);

        try {

            Cloudder::upload($request->file('profile_picture'), null,
                array(
                    "format" => "jpg", "crop" => "crop", "x" => 0, "y" => 0,
                    "width" => 400, "height" => 400, "crop" => "thumb",
                ));
        } catch (Exception $e) {
            return $this->ajaxError('Invalid Image format !');
        }


        $image2 = Cloudder::getResult();

        $image = $image2["public_id"];
        $image1 = $image2["height"];
        $image01 = $image2["width"];

        $image1 = $image1 / $image01;

        $request->session()->push('profile_images', $image . '-' . $image1);

        return Cloudder::show($image, array());

    }

    public function profileskip(Request $request)
    {
        if ($request->session()->has('profile_images')) {

            $images = $request->session()->get('profile_images');
            foreach ($images as $key => $value) {
                Cloudder::destroyImage($value, array());
            }
            $request->session()->forget('profile_images');

        }

        return 'true';

    }

    public function profilesave(Request $request)
    {
        if (!$request->session()->has('profile_images'))
            return $this->ajaxError('Please select a image !');
        else {
            $images = $request->session()->get('profile_images');
            $numItems = count($images);
            $i = 0;
            foreach ($images as $key => $value) {

                if (++$i === $numItems) {
                    $value1 = explode("-", $value);
                    Cloudder::upload(Cloudder::show($value1[0], array()), null,
                        array(
                            "width" => 75, "height" => 75,
                        ));


                    $image = Cloudder::getResult();
                    $image = $image["public_id"];

                    $user = Sentinel::check();
                    $credentials = [
                        'profile_picture_big' => $value1[0],
                        'profile_picture_small' => $image,
                    ];
                    $user = Sentinel::update($user, $credentials);


                    $post1 = $user->post()->create([
                        'text' => '',
                        'type_id' => 3,
                    ]);

                    $post_id = $post1->id;

                    Post_data::create([
                        'source' => $value,
                        'type' => 3,
                        'data' => 'Updated profile picture.',
                        'post_id' => $post_id,

                    ]);
                    event(new NewPost("post"));
                    $this->addNotification($user, $post_id, 0, 3);


                } else {
                    Cloudder::destroyImage($value, array());
                }
                $request->session()->pull('profile_images', $value);
            }

            return 'true';
        }

    }

    public function wallupload(Request $request)
    {
        $this->validate($request, [
            'wall_picture' => 'required | image',
        ]);

        try {

            Cloudder::upload($request->file('wall_picture'), null,
                array(
                    "format" => "jpg",
                    "width" => 1500, "height" => 1000, "crop" => "limit",
                ));
        } catch (Exception $e) {
            return $this->ajaxError('Invalid Image format !');
        }


        $image2 = Cloudder::getResult();

        $image = $image2["public_id"];
        $image1 = $image2["height"];
        $image01 = $image2["width"];

        $image1 = $image1 / $image01;

        $request->session()->push('wall_images', $image . '-' . $image1);


        return Cloudder::show($image, array());

    }

    public function wallskip(Request $request)
    {
        if ($request->session()->has('wall_images')) {

            $images = $request->session()->get('wall_images');
            foreach ($images as $key => $value) {
                Cloudder::destroyImage($value, array());
            }
            $request->session()->forget('wall_images');

        }
        return 'true';

    }

    public function wallsave(Request $request)
    {
        if (!$request->session()->has('wall_images'))
            return $this->ajaxError('Please select a image !');
        else {
            $images = $request->session()->get('wall_images');
            $numItems = count($images);
            $i = 0;
            foreach ($images as $key => $value) {

                if (++$i === $numItems) {

                    $value1 = explode("-", $value);
                    $user = Sentinel::check();
                    $credentials = [
                        'wall_picture_big' => $value1[0],
                        'wall_picture_small' => $value1[0],
                    ];
                    $user = Sentinel::update($user, $credentials);


                    $post1 = $user->post()->create([
                        'text' => '',
                        'type_id' => 3,
                    ]);

                    $post_id = $post1->id;

                    Post_data::create([
                        'source' => $value,
                        'type' => 3,
                        'data' => 'Updated wall picture.',
                        'post_id' => $post_id,

                    ]);
                    event(new NewPost("post"));
                    $this->addNotification($user, $post_id, 0, 4);


                } else {
                    Cloudder::destroyImage($value, array());
                }
                $request->session()->pull('wall_images', $value);
            }

            return 'true';
        }

    }

    public function block(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|exists:users,id',
        ], [
                'user_id.*' => 'Invalid Post!!',
            ]
        );

        $user = Sentinel::check();


        $block = Block::where('user_id1', $user->id)
            ->where('user_id2', $request->user_id)->first();


        if (count($block) > 0) {

            $block->delete();

        } else {

            Block::create([
                'user_id1' => $user->id,
                'user_id2' => $request->user_id
            ]);
        }
    }

    public function followers(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|exists:users,id',
        ], [
                'user_id.*' => 'Invalid Post!!',
            ]
        );

        $Privacy = Privacy::where('user_id', $request->user_id)->first();
        $user = Sentinel::check();
        if ($Privacy) {

            if($Privacy->followers == 1 ){

                $followers = Follow::where('user_id2', $request->user_id)->latest()->get();

                $followData = array();

                foreach ($followers as $follower) {

                    $userprofile = Sentinel::findUserByid($follower->user_id1);

                    $follow = array();
                    $follow["user_id"] = $userprofile->id;
                    $follow["user_name"] = $userprofile->name;
                    $follow["username"] = $userprofile->username;
                    $follow["user_picture"] = Cloudder::show($userprofile->profile_picture_small, array());
                    $carbon1 = Carbon::parse(($follower->created_at))->diffForHumans();
                    $follow["time"] = $this->date_small($carbon1);
                    array_push($followData, $follow);

                }
                return $followData;

            }
            else {

                if ($user->id == $request->user_id) {
                    $followers = Follow::where('user_id2', $request->user_id)->latest()->get();

                    $followData = array();

                    foreach ($followers as $follower) {

                        $userprofile = Sentinel::findUserByid($follower->user_id1);

                        $follow = array();
                        $follow["user_id"] = $userprofile->id;
                        $follow["user_name"] = $userprofile->name;
                        $follow["username"] = $userprofile->username;
                        $follow["user_picture"] = Cloudder::show($userprofile->profile_picture_small, array());
                        $carbon1 = Carbon::parse(($follower->created_at))->diffForHumans();
                        $follow["time"] = $this->date_small($carbon1);
                        array_push($followData, $follow);

                    }
                    return $followData;

                }
                else

                return '-1';
            }
        }
        else{

            $followers = Follow::where('user_id2', $request->user_id)->latest()->get();

            $followData = array();

            foreach ($followers as $follower) {

                $userprofile = Sentinel::findUserByid($follower->user_id1);

                $follow = array();
                $follow["user_id"] = $userprofile->id;
                $follow["user_name"] = $userprofile->name;
                $follow["username"] = $userprofile->username;
                $follow["user_picture"] = Cloudder::show($userprofile->profile_picture_small, array());
                $carbon1 = Carbon::parse(($follower->created_at))->diffForHumans();
                $follow["time"] = $this->date_small($carbon1);
                array_push($followData, $follow);

            }

            return $followData;


        }
    }

    public function following(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|exists:users,id',
        ], [
                'user_id.*' => 'Invalid Post!!',
            ]
        );

        $user = Sentinel::check();

        $Privacy = Privacy::where('user_id', $request->user_id)->first();


        if ($Privacy) {

            if($Privacy->following == 1 ){

                $followers = Follow::where('user_id1', $request->user_id)->latest()->get();

                $followData = array();

                foreach ($followers as $follower) {

                    $userprofile = Sentinel::findUserByid($follower->user_id2);

                    $follow = array();
                    $follow["user_id"] = $userprofile->id;
                    $follow["user_name"] = $userprofile->name;
                    $follow["username"] = $userprofile->username;
                    $follow["user_picture"] = Cloudder::show($userprofile->profile_picture_small, array());
                    $carbon1 = Carbon::parse(($follower->created_at))->diffForHumans();
                    $follow["time"] = $this->date_small($carbon1);
                    array_push($followData, $follow);


                }

                return $followData;

            }
            else {


                if ($user->id == $request->user_id) {
                    $followers = Follow::where('user_id1', $request->user_id)->latest()->get();

                    $followData = array();

                    foreach ($followers as $follower) {

                        $userprofile = Sentinel::findUserByid($follower->user_id2);

                        $follow = array();
                        $follow["user_id"] = $userprofile->id;
                        $follow["user_name"] = $userprofile->name;
                        $follow["username"] = $userprofile->username;
                        $follow["user_picture"] = Cloudder::show($userprofile->profile_picture_small, array());
                        $carbon1 = Carbon::parse(($follower->created_at))->diffForHumans();
                        $follow["time"] = $this->date_small($carbon1);
                        array_push($followData, $follow);


                    }

                    return $followData;


                }
                else

                return '-1';
            }
        }
        else{

            $followers = Follow::where('user_id1', $request->user_id)->latest()->get();

            $followData = array();

            foreach ($followers as $follower) {

                $userprofile = Sentinel::findUserByid($follower->user_id2);

                $follow = array();
                $follow["user_id"] = $userprofile->id;
                $follow["user_name"] = $userprofile->name;
                $follow["username"] = $userprofile->username;
                $follow["user_picture"] = Cloudder::show($userprofile->profile_picture_small, array());
                $carbon1 = Carbon::parse(($follower->created_at))->diffForHumans();
                $follow["time"] = $this->date_small($carbon1);
                array_push($followData, $follow);



            }


            return $followData;


        }
    }

    public function facematches(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|exists:users,id',
        ], [
                'user_id.*' => 'Invalid Post!!',
            ]
        );

        $user = Sentinel::check();

        $Privacy = Privacy::where('user_id', $request->user_id)->first();

        if ($Privacy) {

            if($Privacy->facematch == 1 ){

                $faceData = array();

                $userprofile = Sentinel::findUserByid($request->user_id);

                $facematchs = Facematch::where('user_id1', $request->user_id)
                    ->orwhere('user_id2', $request->user_id)
                    ->whereNotNull('user_ids')
                    ->latest()
                    ->get();

                foreach ($facematchs as $facematch) {
                    $userprofile1 = '';
                    if ($facematch->user_id1 == $request->user_id)
                        $userprofile1 = Sentinel::findUserByid($facematch->user_id2);
                    else
                        $userprofile1 = Sentinel::findUserByid($facematch->user_id1);

                    $face = array();
                    $face["user_id"] = $userprofile1->id;
                    if ($facematch->user_ids == $request->user_id) {

                        $face["user_name"] = $userprofile->name . ' got an up vote against ' . $userprofile1->name;

                    } else {

                        $face["user_name"] = $userprofile->name . ' got a down vote against ' . $userprofile1->name;

                    }

                    $face["username"] = $userprofile1->username;
                    $face["user_picture"] = Cloudder::show($userprofile1->profile_picture_small, array());
                    $carbon1 = Carbon::parse(($facematch->created_at))->diffForHumans();
                    $face["time"] = $this->date_small($carbon1);
                    array_push($faceData, $face);


                }
                return $faceData;
            }
            else{

                if ($user->id == $request->user_id) {

                    $faceData = array();

                    $userprofile = Sentinel::findUserByid($request->user_id);

                    $facematchs = Facematch::where('user_id1', $request->user_id)
                        ->orwhere('user_id2', $request->user_id)
                        ->whereNotNull('user_ids')
                        ->latest()
                        ->get();

                    foreach ($facematchs as $facematch) {
                        $userprofile1 = '';
                        if ($facematch->user_id1 == $request->user_id)
                            $userprofile1 = Sentinel::findUserByid($facematch->user_id2);
                        else
                            $userprofile1 = Sentinel::findUserByid($facematch->user_id1);

                        $face = array();
                        $face["user_id"] = $userprofile1->id;
                        if ($facematch->user_ids == $request->user_id) {

                            $face["user_name"] = $userprofile->name . ' got an up vote against ' . $userprofile1->name;

                        } else {

                            $face["user_name"] = $userprofile->name . ' got a down vote against ' . $userprofile1->name;

                        }

                        $face["username"] = $userprofile1->username;
                        $face["user_picture"] = Cloudder::show($userprofile1->profile_picture_small, array());
                        $carbon1 = Carbon::parse(($facematch->created_at))->diffForHumans();
                        $face["time"] = $this->date_small($carbon1);
                        array_push($faceData, $face);


                    }
                    return $faceData;


                }
                else

                return '-1';
            }
        } else {


            $faceData = array();

            $userprofile = Sentinel::findUserByid($request->user_id);

            $facematchs = Facematch::where('user_id1', $request->user_id)
                ->orwhere('user_id2', $request->user_id)
                ->whereNotNull('user_ids')
                ->latest()
                ->get();

            foreach ($facematchs as $facematch) {
                $userprofile1 = '';
                if ($facematch->user_id1 == $request->user_id)
                    $userprofile1 = Sentinel::findUserByid($facematch->user_id2);
                else
                    $userprofile1 = Sentinel::findUserByid($facematch->user_id1);

                $face = array();
                $face["user_id"] = $userprofile1->id;
                if ($facematch->user_ids == $request->user_id) {

                    $face["user_name"] = $userprofile->name . ' got an up vote against ' . $userprofile1->name;

                } else {

                    $face["user_name"] = $userprofile->name . ' got a down vote against ' . $userprofile1->name;

                }

                $face["username"] = $userprofile1->username;
                $face["user_picture"] = Cloudder::show($userprofile1->profile_picture_small, array());
                $carbon1 = Carbon::parse(($facematch->created_at))->diffForHumans();
                $face["time"] = $this->date_small($carbon1);
                array_push($faceData, $face);


            }
            return $faceData;
        }

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

    public function textLimit($text, $len)
    {

        if (strlen($text) > $len) {
            return substr($text, 0, $len) . "...";
        } else
            return $text;

    }


}
