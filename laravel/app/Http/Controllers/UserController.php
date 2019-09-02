<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Follow;
use App\Notification;
use Carbon\Carbon;
use Cloudder;
use DB;
use Illuminate\Http\Request;
use Sentinel;

class UserController extends Controller
{
    public function online(Request $resquest)
    {

        $formatted_date = Carbon::now()->subSeconds(30)->toDateTimeString();
//        $result = DB::table('users')->where('last_seen','>=',$formatted_date)->select(['id','last_seen'])->get();

        $user = Sentinel::check();


        $result = DB::table('users')
            ->where('id', '!=', $user->id)
            ->where('last_seen','>=',$formatted_date)
            ->select(['id', 'name', 'profile_picture_small', 'last_seen', 'intro'])
            ->orderBy('last_seen','DESC')
            ->get();

        $result1 = DB::table('users')
            ->where('id', '!=', $user->id)
            ->where('last_seen','<',$formatted_date)
            ->where('college_name_id', $user->college_name_id)
            ->where('branch_id', $user->branch_id)
            ->where('college_year', $user->college_year)
            ->select(['id', 'name', 'profile_picture_small', 'last_seen', 'intro'])
            ->orderBy('last_seen','DESC')
            ->get();

        $onlinedata = $this->getUsers($result);

        $onlinedata1 = $this->getUsers($result1);

        $credentials = ['last_seen' => Carbon::now(),];
        Sentinel::update($user, $credentials);
        $onlinedata = array_merge($onlinedata, $onlinedata1);

        $data[1] = $onlinedata;

        return $data;

    }

    public function getUsers($result){

        $onlinedata = array();

        foreach ($result as $key => $value) {

            $online = array();
            $online["user_name"] = $value->name;
            if ($value->intro)
                $online["intro"] = $this->textLimit($value->intro, 15);
            else
                $online["intro"] = '';
            $online["id"] = $value->id;

            $carbon1 = Carbon::parse($value->last_seen)->diffForHumans(null, true);

            if (Carbon::now()->diffInSeconds(Carbon::parse($value->last_seen)) < 31) {

                $online["last_seen"] = "online";

            } else {

                $online["last_seen"] = $this->datesmall($carbon1);
            }


            $online["user_picture"] = Cloudder::show($value->profile_picture_small, array());
            array_push($onlinedata, $online);
        }

        return $onlinedata;
    }

    public function follow(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|exists:users,id',
        ], [
                'user_id.*' => 'Invalid Post!!',
            ]
        );

        $user1 = Sentinel::check();
        $user2 = $request->user_id;

        $follow = Follow::where([
            ['user_id1', $user1->id],
            ['user_id2', $user2],
        ])->first();

        if ($user1->id != $user2) {
            if ($follow) {

                $follow->delete();

                $this->removeNotification($user2, $user1->id, 1, 0);

            } else {

                Follow::create([
                    'user_id1' => $user1->id,
                    'user_id2' => $user2,
                ]);

                $this->addNotification($user2, $user1->id, 1, 0);
                event(new Notify($user2));


            }

        }
    }

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

    }

    public function addNotification($user_id, $source_id, $type, $activity_type)
    {

        Notification::create([
            'source_id' => $source_id,
            'user_id' => $user_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ]);

    }

    public function removeNotification($user_id, $source_id, $type, $activity_type)
    {

        Notification::where([
            'source_id' => $source_id,
            'user_id' => $user_id,
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

    public function textLimit($text, $len)
    {

        if (strlen($text) > $len) {
            return substr($text, 0, $len) . "...";
        } else
            return $text;

    }

}
