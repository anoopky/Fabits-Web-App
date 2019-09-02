<?php

namespace App\Http\Controllers;

use App\Follow;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;

use App\Notification;
use App\UnfollowPost;
use DB;


class ApiNewNotificationController extends Controller
{
    public function newNotification(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $myID = Sentinel::findById($userID);
            $FinalList = array();
            $followers = Follow::where('user_id1', $userID)->select('user_id2')->get();

            $UserNotification = Notification::select(['id', 'source_id', 'type', 'activity_type', 'created_at'])
                ->where('user_id', $userID)
                ->groupby(['source_id', 'type'])
                ->orderby('created_at', 'desc')
                ->offset(0)
                ->limit(15)
                ->get();

            $unFollows = UnfollowPost::where('user_id', $userID)
                ->select('post_id')
                ->get();
            $i = 0;
            foreach ($UserNotification as $notification) {
                foreach ($unFollows as $unFollow) {
                    if ($notification->type == 0 && $unFollow->post_id == $notification->source_id) {
                        unset($UserNotification[$i]);
                    }
                }
                $i++;
            }

            foreach ($UserNotification as $notification) {
                $notify = array();
                $singleNotification = Notification::where('source_id', $notification->source_id)
                    ->where('type', $notification->type)
                    ->where('id', '>', $myID->last_notification)
                    ->where('created_at', '>=', $notification->created_at)
                    ->latest()
                    ->get();

                $groupNotifications = collect($singleNotification)->groupBy('activity_type');

                foreach ($groupNotifications as $groupNotification) {

                    $count = 0;
                    $type = 0;
                    $activity_type = 0;
                    $name = null;
                    $userName = null;
                    $image = null;
                    $suffix = "";
                    $prefix = "";
                    $myImage = Cloudder::show($myID->profile_picture_small, array());
                    $myUsername = $myID->id;
                    $source_id = null;

                    foreach ($groupNotification as $LastNotification) {
                        $type = $LastNotification->type;
                        $activity_type = $LastNotification->activity_type;
                        if ($notification->type == 0 && $notification->activity_type == 0) {

                            $prefix = "of yours";

                        } else if ($LastNotification->type == 0) {
                            $isFound = false;
                            foreach ($followers as $follower) {
                                if ($follower->user_id2 == $LastNotification->user_id) {
                                    $isFound = true;
                                    break;
                                }
                            }
                            if (!$isFound)
                                continue;
                            $prefix = "you follow";

                        }


                        if ($LastNotification->type == 1 || $LastNotification->type == 2 || $LastNotification->type == 3) {
                            if ($LastNotification->user_id != $userID)
                                break;
                        }


                        $user_id = $LastNotification->user_id;
                        if ($count == 0) {
                            $source_id = $LastNotification->source_id;
                            if ($LastNotification->type == 2 || $LastNotification->type == 1)
                                $user_id = $source_id;
                            $user = Sentinel::findById($user_id);
                            $name = $user->name;
                            $userName = $user->username;
                            $image = Cloudder::show($user->profile_picture_small, array());
                        }

                        $count++;
                    }


                    if ($count > 0) {

                        if ($type == 0 && ($activity_type == 1 || $activity_type == 2)) { //post

                            if ($count > 1)
                                $suffix = $name . " and " . ($count - 1) . " more people have";
                            else
                                $suffix = $name . " has";

                        } elseif ($type == 1) { // follow

                            $suffix = $name;
                            $prefix = " you";

                        } elseif ($type == 2) { // like hate

                            if ($count > 1)
                                $suffix = ($count);
                            else
                                $suffix = "";
                            $prefix = $name;

                        } elseif ($type == 3) { // visit

                            if ($count > 1)
                                $suffix = ($count) . "  people have";
                            else
                                $suffix = "Someone has";
                            $prefix = "";
                            $source_id = $myUsername;
                            $image = $myImage;
                        } else {

                            continue;
                        }

                        $notify["message"] = $this->NotificationMessage($type, $activity_type, $suffix, $prefix);
                        $notify["name"] = $name;
                        $notify["image"] = $image;
                        $notify["source_id"] = $source_id;
                        $notify["type"] = $type;
                        $notify["activity_type"] = $activity_type;
                        array_push($FinalList, $notify);
                    }
                }


            }

            $last_noti = Notification::latest()->first()->id;

            DB::table('users')
                ->where('id', $userID)
                ->update([
                    'last_notification' => $last_noti
                ]);

            return $FinalList;

        }
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
        $type[2][1] = " like YOU more than " . $prefix;
        if ($typeid == 2) {
            $prefix = '';
        }
        $type[3][0] = "visited your profile";

        if ($typeid == 2) {
            $suffix = "People";

        }

        $template = $type[$typeid][$activity_type];

        return $suffix . ' ' . $template . ' ' . $prefix . '.';


    }

}
