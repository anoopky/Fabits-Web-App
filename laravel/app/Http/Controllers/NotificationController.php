<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Notification;
use App\UnfollowPost;
use Carbon\Carbon;
use Cloudder;
use DB;
use Illuminate\Http\collection;
use Illuminate\Http\Request;
use Sentinel;

class NotificationController extends Controller
{
    public function show(Request $request, $new = null)
    {
        $FinalList = array();
        $user = Sentinel::check();

        $last_noti = $user->last_notification;
        $last_noti1 = $last_noti;

        $notificationID = $request->session()->get('notificationID');

        $followers = Follow::where('user_id1', $user->id)->select('user_id2')->get();
        $followers = collect($followers)->toArray();
//return $followers;
        if ($notificationID && $new == "new") {

            if ($notificationID > $last_noti1) {

                $last_noti1 = $notificationID;
                $last_noti = $notificationID;
            }
        }
        $notificationlist = Notification::select(['id', 'source_id', 'type', 'activity_type', 'created_at'])
            ->where('user_id', $user->id)
            ->groupby(['source_id','type'])
            ->orderby('created_at', 'desc')
            ->offset(0)
            ->limit(15)
            ->get();

        $unfollows = UnfollowPost::where('user_id', $user->id)
            ->select('post_id')
            ->get();

        $i = 0;

        foreach ($notificationlist as $notification) {
            foreach ($unfollows as $unfollow) {

                if ($notification->type == 0 && $unfollow->post_id == $notification->source_id) {

                    unset($notificationlist[$i]);
                }

            }
            $i++;
        }

        foreach ($notificationlist as $notification) {
            $noti = array();
            $last1 = 0;
            if ($new == "new") {
                $last1 = $last_noti;
            }

            $cont = 0;

            $lists = Notification::where('source_id', $notification->source_id)
                ->where('type', $notification->type)
//                ->where('user_id', '!=', $user->id)
                ->where('id', '>', $last1)
                ->where('created_at', '>=', $notification->created_at)
//                ->groupby([ 'user_id', 'type', 'activity_type', 'source_id'])
//                ->select('notifications.*', DB::raw('max(id) as id1'), DB::raw('max(created_at) as created_atNew'))
//                ->orderby(DB::raw('created_atNew'), 'DESC')
////                ->orderby('id', 'desc')
//                ->offset(0)->limit(10)
                ->latest()
                ->get();
//            if ($new == "new")
//            dd($lists);
//            if($notification->source_id == 847)
//return $lists;



            $list1s = collect($lists)->groupBy('activity_type');
            foreach ($list1s as $list1) {


                $count = 0;
                $suffix = "";
                $prefix = "";
                $href = "/post/one/";
                if ($notification->activity_type == 0 ||
                    $notification->activity_type == 3 ||
                    $notification->activity_type == 4 ||
                    $notification->activity_type == 5 ){
                    $prefix = "of yours";

                }
                if ($notification->activity_type == 1 ||
                    $notification->activity_type == 2  ) {
                    $prefix = "you follow";

                }
                foreach ($list1 as $list) {


                    if ($list->type == 1 || $list->type == 2 || $list->type == 3) {

                        if ($list->user_id != $user->id)
                            $cont = 1;

                    }

                    if ($count == 0) {
                        $suffix = $list;
                        $noti['type'] = $list->type;
                        $noti['activity'] = $list->activity_type;

                    }
                    if ($list->type != 0) {
                        $prefix = "you";
                        $href = "/@";
                    }
                    if ($list->user_id == $user->id && ($list->type == 0)) {
                    } else
                        $count++;

                    if ($list->id > $last_noti)
                        $last_noti = $list->id;
                }

                if ($cont)
                    continue;

                if ($count > 0) {



                    if ($suffix->user_id == $user->id && ($notification->type == 0))
                        continue;
                    if ($notification->type != 0) {

                        $user99 = Sentinel::findById($suffix->source_id);
                        $noti['sourceid'] = $href . $user99->username;
                        $href = "/post/one/";
                        $suffix->user = $user99;

                    } else
                        $noti['sourceid'] = $href . $suffix->source_id;


                    if ($last_noti1 < $suffix->id)
                        $noti['count'] = '1';
                    else
                        $noti['count'] = '0';

//                    $noti['time'] = $this->datesmall(Carbon::parse(($suffix->created_at))->diffForHumans());
                    $noti['time'] = Carbon::parse(($suffix->created_at))->toDateTimeString();

                    $noti['user_picture'] = Cloudder::show($suffix->user->profile_picture_small, array());
                    $suffix1 = '';
                    if ($count > 1) {
                        if ($notification->type == 0)
                            $suffix1 = $suffix->user->name . " and " . ($count - 1) . " more people have";
                        elseif ($notification->type == 3) {
                            $suffix1 = ($count) . "  people have";
                            $prefix = "";
                            $noti['sourceid'] = '/@' . $user->username;
                            $noti['user_picture'] = Cloudder::show($user->profile_picture_small, array());
                        } elseif ($notification->type == 2) {
                            $suffix1 = ($count);
                            $prefix = $suffix->user->name ;
                        }
                    } else {
                        if ($notification->type == 0)
                            $suffix1 = $suffix->user->name . " has";

                        elseif ($notification->type == 1)
                            $suffix1 = $suffix->user->name;

                        elseif ($notification->type == 2) {
                            $suffix1 = "";
                            $prefix = $suffix->user->name;
                        } elseif ($notification->type == 3) {
                            $suffix1 = "Someone has";
                            $prefix = "";
                            $noti['sourceid'] = '/@' . $user->username;
                            $noti['user_picture'] = Cloudder::show($user->profile_picture_small, array());

                        }

                    }

                    $noti['msg'] = $this->NotificationMessage($notification->type, $suffix->activity_type, $suffix1, $prefix);


                    array_push($FinalList, $noti);
                }

            }

            if ($cont)
                continue;
        }

        if ($new != "new")
            $request->session()->put('notificationID', $last_noti);
        else
            $request->session()->put('notificationID', $last_noti);

        usort($FinalList, array( $this, "cmp"));


        $i = 0;
        foreach ($FinalList as $ml) {

            $FinalList[$i]["time"] = $this->datesmall(Carbon::parse(($FinalList[$i]["time"]))->diffForHumans());

            $i++;
        }




        return $FinalList;
    }





    public function show1(Request $request, $new = null)
    {
        $FinalList = array();
        $user = Sentinel::check();

        $last_noti = $user->last_notification;
        $last_noti1 = $last_noti;

        $notificationID = $request->session()->get('notificationID');

        $followers = Follow::where('user_id1', $user->id)->select('user_id2')->get();
        $followers = collect($followers)->toArray();
//return $followers;
        if ($notificationID && $new == "new") {

            if ($notificationID > $last_noti1) {

                $last_noti1 = $notificationID;
                $last_noti = $notificationID;
            }
        }
        $notificationlist = Notification::select(['id', 'source_id', 'type', 'activity_type', 'created_at'])
            ->where('user_id', $user->id)
            ->groupby(['source_id','type'])
            ->orderby('created_at', 'desc')
            ->offset(0)
            ->limit(15)
            ->get();

        $unfollows = UnfollowPost::where('user_id', $user->id)
            ->select('post_id')
            ->get();

        $i = 0;

        foreach ($notificationlist as $notification) {
            foreach ($unfollows as $unfollow) {

                if ($notification->type == 0 && $unfollow->post_id == $notification->source_id) {

                    unset($notificationlist[$i]);
                }

            }
            $i++;
        }

        foreach ($notificationlist as $notification) {
            $noti = array();
            $last1 = 0;
            if ($new == "new") {
                $last1 = $last_noti;
            }

            $cont = 0;

            $lists = Notification::where('source_id', $notification->source_id)
                ->where('type', $notification->type)
//                ->where('user_id', '!=', $user->id)
                ->where('id', '>', $last1)
                ->where('created_at', '>=', $notification->created_at)
//                ->groupby([ 'user_id', 'type', 'activity_type', 'source_id'])
//                ->select('notifications.*', DB::raw('max(id) as id1'), DB::raw('max(created_at) as created_atNew'))
//                ->orderby(DB::raw('created_atNew'), 'DESC')
////                ->orderby('id', 'desc')
//                ->offset(0)->limit(10)
                ->latest()
                ->get();
//            if ($new == "new")
//            dd($lists);
//            if($notification->source_id == 847)
return $lists;



            $list1s = collect($lists)->groupBy('activity_type');
            foreach ($list1s as $list1) {


                $count = 0;
                $suffix = "";
                $prefix = "";
                $href = "/post/one/";
                if ($notification->activity_type == 0 ||
                    $notification->activity_type == 3 ||
                    $notification->activity_type == 4 ||
                    $notification->activity_type == 5 ){
                    $prefix = "of yours";

                }
                if ($notification->activity_type == 1 ||
                    $notification->activity_type == 2  ) {
                    $prefix = "you follow";

                }
                foreach ($list1 as $list) {


                    if ($list->type == 1 || $list->type == 2 || $list->type == 3) {

                        if ($list->user_id != $user->id)
                            $cont = 1;

                    }

                    if ($count == 0) {
                        $suffix = $list;
                        $noti['type'] = $list->type;
                        $noti['activity'] = $list->activity_type;

                    }
                    if ($list->type != 0) {
                        $prefix = "you";
                        $href = "/@";
                    }
                    if ($list->user_id == $user->id && ($list->type == 0)) {
                    } else
                        $count++;

                    if ($list->id > $last_noti)
                        $last_noti = $list->id;
                }

                if ($cont)
                    continue;

                if ($count > 0) {


                    if ($suffix->user_id == $user->id && ($notification->type == 0))
                        continue;
                    if ($notification->type != 0) {

                        $user99 = Sentinel::findById($suffix->source_id);
                        $noti['sourceid'] = $href . $user99->username;
                        $href = "/post/one/";
                        $suffix->user = $user99;

                    } else
                        $noti['sourceid'] = $href . $suffix->source_id;


                    if ($last_noti1 < $suffix->id)
                        $noti['count'] = '1';
                    else
                        $noti['count'] = '0';

//                    $noti['time'] = $this->datesmall(Carbon::parse(($suffix->created_at))->diffForHumans());
                    $noti['time'] = Carbon::parse(($suffix->created_at))->toDateTimeString();

                    $noti['user_picture'] = Cloudder::show($suffix->user->profile_picture_small, array());
                    $suffix1 = '';
                    if ($count > 1) {
                        if ($notification->type == 0)
                            $suffix1 = $suffix->user->name . " and " . ($count - 1) . " more people have";
                        elseif ($notification->type == 3) {
                            $suffix1 = ($count) . "  people have";
                            $prefix = "";
                            $noti['sourceid'] = '/@' . $user->username;
                            $noti['user_picture'] = Cloudder::show($user->profile_picture_small, array());
                        } elseif ($notification->type == 2) {
                            $suffix1 = ($count);
                            $prefix = $suffix->user->name ;
                        }
                    } else {
                        if ($notification->type == 0)
                            $suffix1 = $suffix->user->name . " has";

                        elseif ($notification->type == 1)
                            $suffix1 = $suffix->user->name;

                        elseif ($notification->type == 2) {
                            $suffix1 = "";
                            $prefix = $suffix->user->name;
                        } elseif ($notification->type == 3) {
                            $suffix1 = "Someone has";
                            $prefix = "";
                            $noti['sourceid'] = '/@' . $user->username;
                            $noti['user_picture'] = Cloudder::show($user->profile_picture_small, array());

                        }

                    }

                    $noti['msg'] = $this->NotificationMessage($notification->type, $suffix->activity_type, $suffix1, $prefix);


                    array_push($FinalList, $noti);
                }

            }

            if ($cont)
                continue;
        }

        if ($new != "new")
            $request->session()->put('notificationID', $last_noti);
        else
            $request->session()->put('notificationID', $last_noti);

        usort($FinalList, array( $this, "cmp"));


        $i = 0;
        foreach ($FinalList as $ml) {

            $FinalList[$i]["time"] = $this->datesmall(Carbon::parse(($FinalList[$i]["time"]))->diffForHumans());

            $i++;
        }




        return $FinalList;
    }




    function cmp($a, $b){
        return strcmp($b['time'], $a['time']);
    }

    public function read(Request $request)
    {

        $user = Sentinel::check();

        $last_noti = Notification::latest()->first()->id;

        $credentials = [
            'last_notification' => $last_noti
        ];

        Sentinel::update($user, $credentials);

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

    public function NotificationMessage($typeid, $activity_type, $suffix, $prefix)
    {

        $type[0][0] = "updated his status";
        $type[0][1] = "liked a post";
        $type[0][2] = "commented on a post";
        $type[0][3] = "updated his profile picture";
        $type[0][4] = "updated his wall picture";
        $type[0][5] = "uploaded a photo";
        $type[1][0] = "now follows";

        $type[2][0] = " like " . $prefix . " more than YOU";
        $type[2][1] = " like YOU more than ". $prefix;
        if ($typeid == 2) {
            $prefix ='';
        }
        $type[3][0] = "visited your profile";

        if ($typeid == 2) {
            $suffix = "People";

        }

        $template = $type[$typeid][$activity_type] ;

        return $suffix . ' ' . $template . ' ' . $prefix.'.';


    }

    public function feeds(Request $request, $new = null)
    {
        $FinalList = array();
        $user = Sentinel::check();
        $type = 0;
        $feedsID = $request->session()->get('feedsID');
//        dd($feedsID);
        if (!$new) {
            $type = 1;
            $notificationlist = Notification::latest()->where('type', '<=', '1')->offset(0)->limit(10)->get();
        } else {
            if ($feedsID) {
                $notificationlist = Notification::where('id', '>', $feedsID)
                    ->where('type', '<=', '1')
                    ->get();
            } else {
                $type = 1;
                $notificationlist = Notification::latest()->where('type', '<=', '1')->offset(0)->limit(10)->get();
            }
        }

        $total = count($notificationlist);
        if ($total) {
            $i = 1;
            foreach ($notificationlist as $noti) {
                if ($i == $total)
                    $request->session()->put('feedsID', $noti->id);
                $noti_list = array();
                $noti_list["username"] = $noti->user->username;
                $noti_list["time"] = $this->datesmall(Carbon::parse(($noti->created_at))->diffForHumans());
                $noti_list["image"] = Cloudder::show($noti->user->profile_picture_small, array());

                $n_type = $noti->type;
                $n_a_type = $noti->activity_type;
                $suffix = $noti->user->name;
                $prefix = '';
                if ($n_type == 1) {
                    $prefix = DB::table('users')
                        ->where('id', $noti->source_id)
                        ->select('name', 'profile_picture_small')
                        ->first();

                    $suffix1 = $suffix;
                    $suffix = $prefix->name;
                    $noti_list["image"] = Cloudder::show($prefix->profile_picture_small, array());
                    $prefix = $suffix1;
                    $href = "/@";
                    $noti_list['sourceid'] = $href . $noti_list["username"];

                } else {
                    $href = "/post/one/";
                    $noti_list['sourceid'] = $href . $noti->source_id;
                }
                $noti_list["message"] = $this->NotificationMessage($n_type, $n_a_type, $suffix, $prefix);
                array_push($FinalList, $noti_list);
                $i++;
            }

            if ($type == 1) {

                $FinalList = array_reverse($FinalList);
                return $FinalList;
            } else
                return $FinalList;

        } else return [];

    }
}

//
//
//namespace App\Http\Controllers;
//
//use App\Follow;
//use App\Notification;
//use App\UnfollowPost;
//use Carbon\Carbon;
//use Cloudder;
//use DB;
//use Illuminate\Http\collection;
//use Illuminate\Http\Request;
//use Sentinel;
//
//class NotificationController extends Controller
//{
//    public function show(Request $request, $new = null)
//    {
//        $FinalList = array();
//        $user = Sentinel::check();
//
//        $last_noti = $user->last_notification;
//        $last_noti1 = $last_noti;
//
//        $notificationID = $request->session()->get('notificationID');
//
//        $followers = Follow::where('user_id1', $user->id)->select('user_id2')->get();
////        $followers = collect($followers)->toArray();
////return $followers;
//        if ($notificationID && $new == "new") {
//
//            if ($notificationID > $last_noti1) {
//
//                $last_noti1 = $notificationID;
//                $last_noti = $notificationID;
//            }
//        }
//        $notificationlist = Notification::select(['id', 'source_id', 'type', 'activity_type', 'created_at',
//            DB::raw('min(activity_type) as at')])
//            ->where('user_id', $user->id)
////            ->select()
//            ->groupby(['source_id', 'type'])
//            ->orderby('created_at', 'desc')
//            ->offset(0)
//            ->limit(15)
//            ->get();
//
////        return $notificationlist;
//        $unfollows = UnfollowPost::where('user_id', $user->id)
//            ->select('post_id')
//            ->get();
//
//        $i = 0;
//
//        foreach ($notificationlist as $notification) {
//            foreach ($unfollows as $unfollow) {
//
//                if ($notification->type == 0 && $unfollow->post_id == $notification->source_id) {
//
//                    unset($notificationlist[$i]);
//                }
//
//            }
//            $i++;
//        }
//
//        foreach ($notificationlist as $notification) {
//            $noti = array();
//            $last1 = 0;
//            if ($new == "new") {
//                $last1 = $last_noti;
//            }
//
//            $cont = 0;
//
//            $lists = Notification::where('source_id', $notification->source_id)
//                ->where('type', $notification->type)
////                ->where('user_id', '!=', $user->id)
//                ->where('id', '>', $last1)
//                ->where('created_at', '>=', $notification->created_at)
////                ->groupby([ 'user_id', 'type', 'activity_type', 'source_id'])
////                ->select('notifications.*', DB::raw('max(id) as id1'), DB::raw('max(created_at) as created_atNew'))
////                ->orderby(DB::raw('created_atNew'), 'DESC')
//////                ->orderby('id', 'desc')
////                ->offset(0)->limit(10)
//                ->latest()
//                ->get();
////            if ($new == "new")
////            dd($lists);
////            if($notification->source_id == 847)
////return $lists;
//
//
//            $list1s = collect($lists)->groupBy('activity_type');
//            foreach ($list1s as $list1) {
//
//
//                $count = 0;
//                $suffix = "";
//                $prefix = "";
//                $href = "/post/one/";
//                if ($notification->activity_type == 0 ||
//                    $notification->activity_type == 3 ||
//                    $notification->activity_type == 4 ||
//                    $notification->activity_type == 5 ){
//                    $prefix = "of yours";
//
//                }
//                if ($notification->activity_type == 1 ||
//                 $notification->activity_type == 2  ) {
//                    $prefix = "you follow";
//
//                }
//                $flagSafe = 0;
//
//                foreach ($list1 as $list) {
//
////                    $cont =0;
//
//                    if (($notification->activity_type == 1
//                       || $notification->activity_type == 2)
//                        && $notification->type == 0
//                        && $list->type == 0
//                        && ($list->activity_type == 1
//                        || $list->activity_type == 2)
//                    ) {
//
////                        return 'yes';
//
//                        foreach ($followers as $follower) {
//
//                            if ($follower->user_id2 == $list->user_id) {
//
////                                return $list->source_id;
//                                $flagSafe = 1;
//                                break;
//                            }
//                        }
//
//                        if ($flagSafe == 0) {
////                            return $list->source_id;
//                            $cont = 1;
////                            break;
//                        }
//
//
//                    }
//
//                    if ($list->type == 1 || $list->type == 2 || $list->type == 3) {
//
//                        if ($list->user_id != $user->id)
//                            $cont = 1;
//
//                    }
//
//                    if ($count == 0) {
//                        $suffix = $list;
//                        $noti['type'] = $list->type;
//                        $noti['activity'] = $list->activity_type;
//
//                    }
//                    if ($list->type != 0) {
//                        $prefix = "you";
//                        $href = "/@";
//                    }
//                    if ($list->user_id == $user->id && ($list->type == 0)) {
//                    } else
//                        $count++;
//
//                    if ($list->id > $last_noti)
//                        $last_noti = $list->id;
//                }
//
//                if ($cont)
//                    continue;
//
//                if ($count > 0) {
//
//
//                    if ($suffix->user_id == $user->id && ($notification->type == 0))
//                        continue;
//                    if ($notification->type != 0) {
//
//                        $user99 = Sentinel::findById($suffix->source_id);
//                        $noti['sourceid'] = $href . $user99->username;
//                        $href = "/post/one/";
//                        $suffix->user = $user99;
//
//                    } else
//                        $noti['sourceid'] = $href . $suffix->source_id;
//
//
//                    if ($last_noti1 < $suffix->id)
//                        $noti['count'] = '1';
//                    else
//                        $noti['count'] = '0';
//
////                    $noti['time'] = $this->datesmall(Carbon::parse(($suffix->created_at))->diffForHumans());
//                    $noti['time'] = Carbon::parse(($suffix->created_at))->toDateTimeString();
//
//                    $noti['user_picture'] = Cloudder::show($suffix->user->profile_picture_small, array());
//                    $suffix1 = '';
//                    if ($count > 1) {
//                        if ($notification->type == 0)
//                            $suffix1 = $suffix->user->name . " and " . ($count - 1) . " more people have";
//                        elseif ($notification->type == 3) {
//                            $suffix1 = ($count) . "  people have";
//                            $prefix = "";
//                            $noti['sourceid'] = '/@' . $user->username;
//                            $noti['user_picture'] = Cloudder::show($user->profile_picture_small, array());
//                        } elseif ($notification->type == 2) {
//                            $suffix1 = ($count);
//                            $prefix = $suffix->user->name;
//                        }
//                    } else {
//                        if ($notification->type == 0)
//                            $suffix1 = $suffix->user->name . " has";
//
//                        elseif ($notification->type == 1)
//                            $suffix1 = $suffix->user->name;
//
//                        elseif ($notification->type == 2) {
//                            $suffix1 = "";
//                            $prefix = $suffix->user->name;
//                        } elseif ($notification->type == 3) {
//                            $suffix1 = "Someone has";
//                            $prefix = "";
//                            $noti['sourceid'] = '/@' . $user->username;
//                            $noti['user_picture'] = Cloudder::show($user->profile_picture_small, array());
//
//                        }
//
//                    }
//
//                    $noti['msg'] = $this->NotificationMessage($notification->type, $suffix->activity_type, $suffix1, $prefix);
//
//
//                    array_push($FinalList, $noti);
//                }
//
//            }
//
//            if ($cont)
//                continue;
//        }
//
//        if ($new != "new")
//            $request->session()->put('notificationID', $last_noti);
//        else
//            $request->session()->put('notificationID', $last_noti);
//
//        usort($FinalList, array($this, "cmp"));
//
//
//        $i = 0;
//        foreach ($FinalList as $ml) {
//
//            $FinalList[$i]["time"] = $this->datesmall(Carbon::parse(($FinalList[$i]["time"]))->diffForHumans());
//
//            $i++;
//        }
//
//
//        return $FinalList;
//    }
//
//
//    function cmp($a, $b)
//    {
//        return strcmp($b['time'], $a['time']);
//    }
//
//    public function read(Request $request)
//    {
//
//        $user = Sentinel::check();
//
//        $last_noti = Notification::latest()->first()->id;
//
//        $credentials = [
//            'last_notification' => $last_noti
//        ];
//
//        Sentinel::update($user, $credentials);
//
//    }
//
//    public function datesmall($Date)
//    {
//        $Date = str_replace("second", "s", $Date);
//        $Date = str_replace("ss", "s", $Date);
//        $Date = str_replace("minute", "m", $Date);
//        $Date = str_replace("ms", "m", $Date);
//        $Date = str_replace("hour", "h", $Date);
//        $Date = str_replace("hs", "h", $Date);
//        $Date = str_replace("day", "d", $Date);
//        $Date = str_replace("ds", "d", $Date);
//        $Date = str_replace("week", "w", $Date);
//        $Date = str_replace("ws", "w", $Date);
//        $Date = str_replace("year", "y", $Date);
//        $Date = str_replace("ys", "y", $Date);
//        return $Date;
//    }
//
//    public function NotificationMessage($typeid, $activity_type, $suffix, $prefix)
//    {
//
//        $type[0][0] = "updated his status";
//        $type[0][1] = "liked a post";
//        $type[0][2] = "commented on a post";
//        $type[0][3] = "updated his profile picture";
//        $type[0][4] = "updated his wall picture";
//        $type[0][5] = "uploaded a photo";
//        $type[1][0] = "now follows";
//
//        $type[2][0] = " like " . $prefix . " more than YOU";
//        $type[2][1] = " like YOU more than " . $prefix;
//        if ($typeid == 2) {
//            $prefix = '';
//        }
//        $type[3][0] = "visited your profile";
//
//        if ($typeid == 2) {
//            $suffix = "People";
//
//        }
//
//        $template = $type[$typeid][$activity_type];
//
//        return $suffix . ' ' . $template . ' ' . $prefix . '.';
//
//
//    }
//
//    public function feeds(Request $request, $new = null)
//    {
//        $FinalList = array();
//        $user = Sentinel::check();
//        $type = 0;
//        $feedsID = $request->session()->get('feedsID');
////        dd($feedsID);
//        if (!$new) {
//            $type = 1;
//            $notificationlist = Notification::latest()->where('type', '<=', '1')->offset(0)->limit(10)->get();
//        } else {
//            if ($feedsID) {
//                $notificationlist = Notification::where('id', '>', $feedsID)
//                    ->where('type', '<=', '1')
//                    ->get();
//            } else {
//                $type = 1;
//                $notificationlist = Notification::latest()->where('type', '<=', '1')->offset(0)->limit(10)->get();
//            }
//        }
//
//        $total = count($notificationlist);
//        if ($total) {
//            $i = 1;
//            foreach ($notificationlist as $noti) {
//                if ($i == $total)
//                    $request->session()->put('feedsID', $noti->id);
//                $noti_list = array();
//                $noti_list["username"] = $noti->user->username;
//                $noti_list["time"] = $this->datesmall(Carbon::parse(($noti->created_at))->diffForHumans());
//                $noti_list["image"] = Cloudder::show($noti->user->profile_picture_small, array());
//
//                $n_type = $noti->type;
//                $n_a_type = $noti->activity_type;
//                $suffix = $noti->user->name;
//                $prefix = '';
//                if ($n_type == 1) {
//                    $prefix = DB::table('users')
//                        ->where('id', $noti->source_id)
//                        ->select('name', 'profile_picture_small')
//                        ->first();
//
//                    $suffix1 = $suffix;
//                    $suffix = $prefix->name;
//                    $noti_list["image"] = Cloudder::show($prefix->profile_picture_small, array());
//                    $prefix = $suffix1;
//                    $href = "/@";
//                    $noti_list['sourceid'] = $href . $noti_list["username"];
//
//                } else {
//                    $href = "/post/one/";
//                    $noti_list['sourceid'] = $href . $noti->source_id;
//                }
//                $noti_list["message"] = $this->NotificationMessage($n_type, $n_a_type, $suffix, $prefix);
//                array_push($FinalList, $noti_list);
//                $i++;
//            }
//
//            if ($type == 1) {
//
//                $FinalList = array_reverse($FinalList);
//                return $FinalList;
//            } else
//                return $FinalList;
//
//        } else return [];
//
//    }
//}
