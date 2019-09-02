<?php

namespace App\Http\Controllers;

use App\Events\SeenReadConversation;
use App\GroupList;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use DB;


class ApiNewMessageController extends Controller
{
    public function newMessage(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $result = GroupList::
            where('group_lists.user_id', $userID)
                ->whereColumn('messagesV2.id', '>', 'group_lists.lastseen')
                ->join('conversationsV2', 'group_lists.conversationsV2_id', '=', 'conversationsV2.id')
                ->join('messagesV2', 'group_lists.conversationsV2_id', '=', 'messagesV2.conversationsV2_id')
                ->join('users', 'messagesV2.user_id', '=', 'users.id')
                ->select('*', 'users.id as uid', 'messagesV2.created_at as created_at ')
                ->orderBy('messagesV2.conversationsV2_id')
                ->orderBy('messagesV2.id')
                ->get();

            $userProfile = Sentinel::findById($userID);

            $chatList = array();
            $isIDSame = -1;

            foreach ($result as $res) {

                $checkAuth = GroupList::where('user_id', '!=', $userID)
                    ->where('conversationsV2_id', $res->conversationsV2_id)->first();

                if ($checkAuth->auth == 0)
                    continue;

                $chat = array();
                $chat["id"] = $res->id;
                if ($isIDSame != $res->conversationsV2_id) {
                    $this->UpdateMessageStatus_Delivered($userID, $res->conversationsV2_id, $res->id);
                    $isIDSame = $res->conversationsV2_id;

                }


                if ($res->type == 1 || $res->type == 2) {

                    $chat["id"] = $res->id;
                    $chat["name"] = $res->uid;
                    $chat["names"] = $res->name;
                    $chat["userIDS"] = (string)$res->user->id;
                    $chat["image"] = Cloudder::show($res->profile_picture_small, array());
                    $chat["conversation_id"] = $res->conversationsV2_id;
                    $chat["count"] = $res->count;
                    $chat["auth"] = $res->count;
                    $chat["type"] = $res->type;
                    $chat["time"] = Carbon::parse(($res->created_at))->format('h:i a');
                    $chat["time_tag"] = Carbon::parse(($res->created_at))->toFormattedDateString();
                    $chat["message"] = $res->message;

                }

                $list = GroupList::where('conversationsV2_id', $res->conversationsV2_id)->get();
//                $userProfile = Sentinel::findById($userID);


                $this->SocketSeen($userID, $userProfile, $list);


                array_push($chatList, $chat);
            }


            return $chatList;

        }
    }

    public function SocketSeen($userId, $userProfile, $list)
    {

        $messageSocket = array();
        $otherId = null;
        $online = $userProfile->last_seen;
        $carbon1 = Carbon::parse($online)->diffForHumans(null, true);
        if (Carbon::now()->diffInSeconds(Carbon::parse($online)) < 31) {
            $online = "online";
        } else {
            $online = $this->datesmall($carbon1);
        }

        $messageSocket["lastSeen"] = $online;
        $messageSocket["userID"] = $userProfile->id;

        $messageSocket["image"] = Cloudder::show($userProfile->profile_picture_small, array());


        foreach ($list as $item) {
            if ($userId == $item->user_id) {
                $messageSocket["lastDelivered"] = $item->lastseen;
                $messageSocket["lastRead"] = $item->lastread;
                $messageSocket["conversation_id"] = $item->conversationsV2_id;
            } else
                $otherId = $item->user_id;

        }


        event(new SeenReadConversation($otherId, $messageSocket));

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

    public function UpdateMessageStatus_Delivered($userID, $ConversationID, $lastDelivered)
    {

        GroupList::where([
            'conversationsV2_id' => $ConversationID,
            'user_id' => $userID
        ])->update(['lastseen' => $lastDelivered]);
    }


}
