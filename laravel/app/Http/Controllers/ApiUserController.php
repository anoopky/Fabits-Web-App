<?php

namespace App\Http\Controllers;


use App\Block;
use App\Follow;
use App\Notification;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use DB;


class ApiUserController extends Controller
{

    public function my_following(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $following = Follow::where('user_id1', $userID)
                ->select('user_id2')->get();

            return $following;
        }
    }

    public function Block(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $user_id = $request->user_id;


            $block = Block::where([
                ['user_id1', $userID],
                ['user_id2', $user_id],
            ])->first();

            if ($userID != $user_id) {
                if ($block) {

                    $block->delete();
                    return '0';

                } else {

                    Block::create([
                        'user_id1' => $userID,
                        'user_id2' => $user_id,
                    ]);
                    return '1';
                }
            }

        }
    }

    public function my_blocks(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $following = Block::where('user_id1', $userID)
                ->select('user_id2')->get();
            return $following;
        }
    }

    public function my_block_list(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $blocks = Block::where('user_id1', $userID)->latest()->get();

            $blockData = array();

            foreach ($blocks as $block) {

                $userProfile = Sentinel::findUserByid($block->user_id2);
                $b = array();
                $b["user_id"] = $userProfile->id;
                $b["user_name"] = $userProfile->name;
                $b["username"] = $userProfile->username;
                $b["user_picture"] = Cloudder::show($userProfile->profile_picture_small, array());
                $carbon1 = Carbon::parse(($block->created_at))->diffForHumans();
                $b["time"] = $this->date_small($carbon1);
                array_push($blockData, $b);

            }
            return $blockData;

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


    public function follow(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $user1 = $user->user_id;
            $user2 = $request->user_id;

            $follow = Follow::where([
                ['user_id1', $user1],
                ['user_id2', $user2],
            ])->first();

            if ($user1 != $user2) {
                if ($follow) {

                    $follow->delete();
                    $this->removeNotification($user2, $user1, 1, 0);

                    return '0';

                } else {

                    Follow::create([
                        'user_id1' => $user1,
                        'user_id2' => $user2,
                    ]);
                    $this->addNotification($user2, $user1, 1, 0);

                    return '1';
                }
            }
        }
    }

    public function suggestion(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $user = Sentinel::findUserByid($userID);
            $searchList = array();
            $searches = DB::table('users')
                ->where('id', '!=', $user->id)
                ->where('college_name_id', $user->college_name_id)
                ->orderBy(DB::raw('RAND()'))
                ->offset(0)
                ->limit(10)
                ->get();

            foreach ($searches as $search) {

                $search_user = array();
                $search_user["user_name"] = $search->name;
                $search_user["user_id"] = $search->id;
                if ($search->id == $user->id)
                    continue;
                $search_user["username"] = $search->username;
                $search_user["time"] = $search->intro;
                $search_user["user_picture"] = Cloudder::show($search->profile_picture_big, array());

                $isfollow = Follow::where('user_id2', $search->id)
                    ->where('user_id1', $user->id)
                    ->select('id')->count();

                if ($isfollow > 0)
                    continue;

                array_push($searchList, $search_user);
            }

            return $searchList;

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

}
