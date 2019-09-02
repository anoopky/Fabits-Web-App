<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use DB;


class ApiOnlineController extends Controller
{
    public function online(Request $resquest, $token)
    {

        $formatted_date = Carbon::now()->subSeconds(60)->toDateTimeString();
//        $result = DB::table('users')->where('last_seen','>=',$formatted_date)->select(['id','last_seen'])->get();

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $user = Sentinel::findById($user->user_id);

            $result = DB::table('users')
                ->where('id', '!=', $user->id)
                ->where('last_seen', '>=', $formatted_date)
                ->select(['id', 'name', 'profile_picture_small', 'last_seen', 'intro'])
                ->orderBy('last_seen', 'DESC')
                ->get();

            $result1 = DB::table('users')
                ->where('id', '!=', $user->id)
                ->where('last_seen', '<', $formatted_date)
                ->where('college_name_id', $user->college_name_id)
                ->where('branch_id', $user->branch_id)
                ->where('college_year', $user->college_year)
                ->select(['id', 'name', 'profile_picture_small', 'last_seen', 'intro'])
                ->orderBy('last_seen', 'DESC')
                ->get();

            $onlinedata = $this->getUsers($result);
            $onlinedata1 = $this->getUsers($result1);
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_seen' => Carbon::now(),]);
            $onlinedata = array_merge($onlinedata, $onlinedata1);

            $data = $onlinedata;

            return $data;
        }
    }

    public function getUsers($result)
    {

        $onlinedata = array();

        foreach ($result as $key => $value) {

            $online = array();
            $online["user_name"] = $value->name;
            if ($value->intro)
                $online["intro"] = $this->textLimit($value->intro, 30);
            else
                $online["intro"] = '';
            $online["id"] = $value->id;

            $carbon1 = Carbon::parse($value->last_seen)->diffForHumans(null, true);

            if (Carbon::now()->diffInSeconds(Carbon::parse($value->last_seen)) < 61) {

                $online["last_seen"] = "online";

            } else {

                $online["last_seen"] = $this->datesmall($carbon1);
            }


            $online["user_picture"] = Cloudder::show($value->profile_picture_small, array());
            array_push($onlinedata, $online);
        }

        return $onlinedata;
    }

    public function textLimit($text, $len)
    {

        if (strlen($text) > $len) {
            return substr($text, 0, $len) . "...";
        } else
            return $text;

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



}
