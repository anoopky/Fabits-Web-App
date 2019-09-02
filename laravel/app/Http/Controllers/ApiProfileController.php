<?php

namespace App\Http\Controllers;

use App\Block;
use App\Branch;
use App\College_name;
use App\Facematch;
use App\Follow;
use App\Post_data;
use App\Profileview;
use App\Relationship;
use Illuminate\Http\Request;
use App\oAuth;
use App\Privacy;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use App\Notification;
use App\Post;
use DB;

class ApiProfileController extends Controller
{
    public function profile(Request $request, $token, $username)
    {

        $userProfile = Sentinel::findById($username);


        if (count($userProfile)) {
            $profileData = [];

            $user = oAuth::where([
                'token' => $token,
            ])->first();

            if ($user) {

                $user->id = $user->user_id;
                $followers = Follow::where('user_id2', $userProfile->id)
                    ->select('id')->count();
                $isFollow = Follow::where('user_id2', $userProfile->id)
                    ->where('user_id1', $user->id)
                    ->select('id')->count();
                $following = Follow::where('user_id1', $userProfile->id)
                    ->select('id')->count();
                $profileViews = Profileview::where('user_id2', $userProfile->id)
                    ->select('id')->count();

                $profileViews_user = Profileview::where('user_id2', $userProfile->id)
                    ->where('user_id1', $user->id)
                    ->latest()->first();

                $Privacy = Privacy::where('user_id', $userProfile->id)->first();

                if ($profileViews_user) {
                    $carbon1 = Carbon::parse(($profileViews_user->created_at))->diffInSeconds();

                    if ($carbon1 > 60) {
                        if ($user->id != $userProfile->id) {

                            Profileview::create([
                                'user_id1' => $user->id,
                                'user_id2' => $userProfile->id
                            ]);
                            $this->addNotification($userProfile->id, $user->id, 3, 0);

//                            $this->addNotification($userProfile, $user->id, 3, 0);
//                            event(new Notify($userProfile->id));

                        }
                    }

                } else {
                    if ($user->id != $userProfile->id) {
                        Profileview::create([
                            'user_id1' => $user->id,
                            'user_id2' => $userProfile->id
                        ]);
                        $this->addNotification($userProfile->id, $user->id, 3, 0);

                        //                        $this->addNotification($userProfile, $user->id, 3, 0);
//                        event(new Notify($userProfile->id));


                    }
                }
                $postCount = Post::where('user_id', $userProfile->id)->select('id')
                    ->count();

                $isBlock = Block::where('user_id2', $userProfile->id)
                    ->where('user_id1', $user->id)
                    ->select('id')->count();

                $faceMatches = Facematch::where('user_id1', $userProfile->id)
                    ->orwhere('user_id2', $userProfile->id)
                    ->whereNotNull('user_ids')
                    ->select('id', 'user_ids')
//                ->where('user_ids')
                    ->get();

                $total = 0;
                $select = 0;
                foreach ($faceMatches as $faceMatch) {

                    if ($faceMatch->user_ids == $userProfile->id)
                        $select++;
                    $total++;
                }

                $yearCurr = Carbon::now()->year - "$userProfile->college_year";


                $collegeName = College_name::where('id', $userProfile->college_name_id)->select('name')->first()->name;

                $branchName = Branch::where('id', $userProfile->branch_id)->select('name')->first()->name;

                $profileData["college"] = $collegeName;
                $profileData["branch"] = $branchName;
                if ($yearCurr == 0)
                    $profileData["year"] = "";
                else
                    $profileData["year"] = "0" . $yearCurr;
                $profileData["followers"] = $followers;
                $profileData["following"] = $following;
                $profileData["profile_views"] = $profileViews;
                $profileData["posts"] = $postCount;
                $profileData["isFollow"] = $isFollow;
                $profileData["isBlock"] = $isBlock;

                if ($total == 0)
                    $profileData["faceMatch_Rating"] = "N/10";
                else
                    $profileData["faceMatch_Rating"] = round(($select / $total) * 10, 1) . "/10";

                if ($userProfile["birth_day"])
                    $dob = $userProfile["birth_day"] . " " . $this->monthname($userProfile["birth_month"]);
                else

                    $dob = '-';

                if($userProfile["phone"] == null){
                    $userProfile["phone"]= -1;
                }
                $userProfile["wall_picture_big"] = Cloudder::show($userProfile["wall_picture_big"], array());
                $userProfile["wall_picture_small"] = Cloudder::show($userProfile["wall_picture_small"], array());
                $userProfile["profile_picture_big"] = Cloudder::show($userProfile["profile_picture_big"], array());
                $userProfile["profile_picture_small"] = Cloudder::show($userProfile["profile_picture_small"], array());
                $profileData["wall_picture_big"] = $userProfile["wall_picture_big"];
                $profileData["wall_picture_small"] = $userProfile["wall_picture_small"];
                $profileData["profile_picture_big"] = $userProfile["profile_picture_big"];
                $profileData["profile_picture_small"] = $userProfile["profile_picture_small"];

                $profileData["id"] = $userProfile["id"];
                $profileData["location"] = $userProfile["hometown"];
                $profileData["username"] = $userProfile["username"];
                $profileData["name"] = $userProfile["name"];
                $profileData["intro"] = $userProfile["intro"];
                $profileData["relationship"] = Relationship::where([
                    'id' => $userProfile["relationship_id"]
                ])->first()->name;
                $profileData["dob"] = $dob;

                if ($Privacy != null) {

                        if ($Privacy->phone == 0)
                            $profileData["phone"] = -1;
                        else
                            $profileData["phone"] = $userProfile["phone"];
                } else {

                    $profileData["phone"] = "-1";
                }
                if ($user->id == $userProfile->id) {


                    $profileData["phone"] = $userProfile["phone"];
                }


                $json = json_encode($profileData);

                return $json;

            } else {

                return '-1';
            }
        } else {


            return '-1';
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

    public function randomProfiles(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $myID = Sentinel::findById($userID);

            $searches = DB::table('users')
                ->where('id', '!=', $userID)
                ->where('gender', '!=', $myID->gender)
                ->orderBy(DB::raw('RAND()'))
                ->paginate(5);

            $profiles = array();
            foreach ($searches as $item) {
                $result = array();
                $result["profile_picture_big"] = Cloudder::show($item->profile_picture_big, array());
                $result["name"] = $item->name;
                $result["id"] = $item->id;
                $result["username"] = $item->username;
                $result["intro"] = $item->intro;
                array_push($profiles, $result);
            }

            return $profiles;

        }
    }

    public function profileCountLists(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $type = $request->type;
            $profileUser = $request->source_id;

            $Privacy = Privacy::where('user_id', $profileUser)->first();

            if ($type == 0) {


                $followers = Follow::where('user_id2', $profileUser)->latest()->paginate(10);
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

                if ($Privacy) {

                    if ($Privacy->followers == 1 || $profileUser == $userID) {
                        return $followData;
                    }
                    return "-1";

                }

                return $followData;
            } else if ($type == 1) {


                $followers = Follow::where('user_id1', $profileUser)->latest()->paginate(10);
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

                if ($Privacy) {

                    if ($Privacy->following == 1 || $profileUser == $userID) {
                        return $followData;
                    }
                    return "-1";

                }

                return $followData;

            } else if ($type == 2) {

                $faceData = array();

                $userprofile = Sentinel::findUserByid($profileUser);

                $facematchs = Facematch::where('user_id1', $profileUser)
                    ->orwhere('user_id2', $profileUser)
                    ->whereNotNull('user_ids')
                    ->latest()
                    ->paginate(10);

                foreach ($facematchs as $facematch) {
                    $userprofile1 = '';
                    if ($facematch->user_id1 == $profileUser)
                        $userprofile1 = Sentinel::findUserByid($facematch->user_id2);
                    else
                        $userprofile1 = Sentinel::findUserByid($facematch->user_id1);

                    $face = array();
                    $face["user_id"] = $userprofile1->id;
                    if ($facematch->user_ids == $profileUser) {

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

                if ($Privacy) {

                    if ($Privacy->facematch == 1 || $profileUser == $userID) {
                        return $faceData;
                    }
                    return "-1";

                }

                return $faceData;

            }

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


    public function profilePic(Request $request, $token)
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

            $post_id = $post1->id;

            try {

                Cloudder::upload($request->file('uploadfile'), null,
                    array(
                        "format" => "jpg", "crop" => "crop", "x" => 0, "y" => 0,
                        "width" => 500, "height" => 500, "crop" => "thumb",
                    ));
            } catch (\Exception $e) {
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
                'data' => 'Updated profile picture.',
                'post_id' => $post_id,

            ]);

            try {

                Cloudder::upload(Cloudder::show($image, array()), null,
                    array(
                        "width" => 75, "height" => 75,
                    ));

            } catch (\Exception $e) {
                return 'Invalid Image format !';
            }

            $image007 = Cloudder::getResult();
            $image008 = $image007["public_id"];

            DB::table('users')
                ->where('id', $userID)
                ->update([
                    'profile_picture_big' => $image,
                    'profile_picture_small' => $image008,
                ]);

            return Cloudder::show($image, array());


        }
    }


    public function profileWall(Request $request, $token)
    {


        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;


            $type_id = 3;
            $post_id = null;
            $postData = $request->metadata;

            $post1 = Post::create([
                'text' => $postData,
                'type_id' => $type_id,
                'user_id' => $userID
            ]);

            $post_id = $post1->id;

            try {

                Cloudder::upload($request->file('uploadfile'), null,
                    array(
                        "format" => "jpg",
                        "width" => 1500, "height" => 1000, "crop" => "limit",
                    ));

            } catch (\Exception $e) {
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
                'data' => 'Updated wall picture.',
                'post_id' => $post_id,

            ]);

            DB::table('users')
                ->where('id', $userID)
                ->update([
                    'wall_picture_big' => $image,
                    'wall_picture_small' => $image,
                ]);

            return Cloudder::show($image, array());


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

    public function bigImage(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $request->user_id;
            $userProfile = Sentinel::findUserByid($userID);
            return Cloudder::show($userProfile->profile_picture_big, array());
        }
    }

}
