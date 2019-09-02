<?php

namespace App\Http\Controllers;

use App\Conversationv2;
use App\Events\SeenReadConversation;
use App\GroupList;
use App\MessageV2;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use DB;
class ApiMessageController extends Controller
{


    public function SMessage(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $message = $request->message;
            $ConversationID = $request->conversation_Id;

            $Message = MessageV2::create([
                'conversationsV2_id' => $ConversationID,
                'message' => $message,
                'user_id' => $userID
            ]);

            $id = $Message->id;
//            $authList = Conversationv2::where('conversationsV2_id', $ConversationID)->first();
            $this->UpdateMessageStatus($userID, $ConversationID, $id, $id);

            $userProfile = Sentinel::findById($userID);
            $list = GroupList::where('conversationsV2_id', $ConversationID)->get();

            $this->SocketMessage($Message, $userProfile, $list);

            $this->SocketSeen($userID, $userProfile, $list);

            $data = array();
            $data["id"] = $id;
            $data["message"] = $message;
            $data["time"] = Carbon::parse(($Message->created_at))->format('h:i a');
            $data["time_tag"] = Carbon::parse(($Message->created_at))->toFormattedDateString();
            return $data;

        }
    }


    public function CMessage(Request $request, $token)
    {


        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $message = $request->message;
            $toUser = $request->conversation_Id;


            $isConversationExist = GroupList::where('user_id', $userID)
                ->join('conversationsV2', 'group_lists.conversationsV2_id', '=', 'conversationsV2.id')
                ->where('type', 1)
                ->whereIn('conversationsV2_id', function ($query) use ($toUser) {

                    $query->select('conversationsV2_id')->from('group_lists')->where('user_id', $toUser)
                        ->join('conversationsV2', 'group_lists.conversationsV2_id', '=', 'conversationsV2.id')
                        ->where('type', 1)
                        ->get();
                })
                ->first();


            $data = array();

            if (count($isConversationExist) > 0) {


                $Message = MessageV2::create([
                    'conversationsV2_id' => $isConversationExist->id,
                    'message' => $message,
                    'user_id' => $userID
                ]);


                GroupList::where('user_id', '!=', $userID)
                    ->where('conversationsV2_id', $isConversationExist->id)
                    ->update([
                        'auth' => '2',
                    ]);

                $userProfile = Sentinel::findById($toUser);
                $id = $Message->id;
                $ConversationID = $Message->conversationsV2_id;
                $data["id"] = $id;
                $data["name"] = $userProfile->name;
                $data["userID"] = $toUser;
                $data["userIDS"] = $userID;
                $data["image"] = Cloudder::show($userProfile->profile_picture_small, array());
                $data["count"] = "0";
                $data["auth"] = "2";
                $data["conversation_id"] = $ConversationID;
                $data["time"] = Carbon::parse(($Message->created_at))->format('h:i a');
                $data["time_tag"] = Carbon::parse(($Message->created_at))->toFormattedDateString();
                $data["message"] = $Message->message;


                $this->UpdateMessageStatus($userID, $ConversationID, $id, $id);
                $userProfile = Sentinel::findById($userID);
                $list = GroupList::where('conversationsV2_id', $ConversationID)->get();

                $this->SocketMessage($Message, $userProfile, $list);

                $this->SocketSeen($userID, $userProfile, $list);

            } else {

                $id = Conversationv2::insertGetId([
                    'type' => 1,
                    'name' => "",
                    'image' => ""
                ]);


                GroupList::insert([
                    'user_id' => $userID,
                    'conversationsV2_id' => $id,
                    'status' => 0,
                    'auth' => '1',
                    'lastseen' => 0,
                ]);

                GroupList::insert([
                    'user_id' => $toUser,
                    'conversationsV2_id' => $id,
                    'status' => 0,
                    'auth' => '2',
                    'lastseen' => 0,
                ]);

                $Message = MessageV2::create([
                    'conversationsV2_id' => $id,
                    'message' => $message,
                    'user_id' => $userID
                ]);

                $userProfile = Sentinel::findById($toUser);
                $id = $Message->id;
                $ConversationID = $Message->conversationsV2_id;
                $data["id"] = $id;
                $data["name"] = $userProfile->name;
                $data["userID"] = $toUser;
                $data["userIDS"] = $userID;
                $data["image"] = Cloudder::show($userProfile->profile_picture_small, array());
                $data["count"] = "0";
                $data["auth"] = "2";
                $data["conversation_id"] = $ConversationID;
                $data["time"] = Carbon::parse(($Message->created_at))->format('h:i a');
                $data["time_tag"] = Carbon::parse(($Message->created_at))->toFormattedDateString();
                $data["message"] = $Message->message;

                $this->UpdateMessageStatus($userID, $ConversationID, $id, $id);
                $userProfile = Sentinel::findById($userID);
                $list = GroupList::where('conversationsV2_id', $ConversationID)->get();

                $this->SocketMessage($Message, $userProfile, $list);

                $this->SocketSeen($userID, $userProfile, $list);
            }

            return array($data);

        }
    }

    public function conversationInit(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $conversationId = $request->conversationId;
            $lastRead = null;
            $lastDelivered = null;
            $lastSeen = null;

            $group = GroupList::where('conversationsV2_id', $conversationId)
                ->where('user_id', '!=', $userID)
                ->first();


            if (count($group) > 0) {
                $otherUserID = $group->user_id;
                $lastDelivered = $group->lastseen;
                $lastRead = $group->lastread;
            } else {

            $otherUserID = $conversationId;
                $lastDelivered = "-1";
                $lastRead = "-1";
        }

            $last = DB::table('users')
                ->where('id', $otherUserID)
                ->select('last_seen', 'profile_picture_small')
                ->first();

            $online = $last->last_seen;
            $carbon1 = Carbon::parse($online)->diffForHumans(null, true);
            if (Carbon::now()->diffInSeconds(Carbon::parse($online)) < 61) {
                $online = "online";
            } else {
                $online = $this->datesmall($carbon1);
            }

            return array(array(
                'lastDelivered' => $lastDelivered,
                'conversation_id' => $conversationId,
                'lastRead' => $lastRead,
                'lastSeen' => $online,
                'userID' => $otherUserID,
                'image' => Cloudder::show($last->profile_picture_small, array())
            ));
        }

    }


    public function readConversation(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $conversationId = $request->conversationId;
            $lastRead = $request->lastRead;
            $lastReceived = $request->lastReceived;


            if (strlen($lastReceived) > 0) {

                $this->UpdateMessageStatus_Delivered($userID, $conversationId, $lastReceived);
                $list = GroupList::where('conversationsV2_id', $conversationId)->get();
                $userProfile = Sentinel::findById($userID);


                $this->SocketSeen($userID, $userProfile, $list);

            } else {

                $this->UpdateMessageStatus($userID, $conversationId, $lastRead, $lastRead);
                $list = GroupList::where('conversationsV2_id', $conversationId)->get();
                $userProfile = Sentinel::findById($userID);


                $this->SocketSeen($userID, $userProfile, $list);


            }

        }

    }

    public function forceReadConversation(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $conversationId = $request->conversationId;
            $Message = MessageV2:: where('conversationsV2_id', $conversationId)->latest()->first();
            $this->UpdateMessageStatus($userID, $conversationId, $Message->id, $Message->id);
            $list = GroupList::where('conversationsV2_id', $conversationId)->get();
            $userProfile = Sentinel::findById($userID);


            $this->SocketSeen($userID, $userProfile, $list);
        }

    }


    public function chatImageUpload(Request $request, $token)
    {

//        return "WTF";
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $postData = $request->metadata;
            $ConversationID = $request->conversation_Id;
//            return $ConversationID;
            try {

                Cloudder::upload($request->file('uploadfile'), null,
                    array(
                        "format" => "jpg",
                        "width" => 500, "height" => 500, "crop" => "limit",
                    ));

            } catch (\Exception $e) {
                return 'Invalid Image format !';
            }

            $image2 = Cloudder::getResult();
            $image = $image2["public_id"];
            $image = Cloudder::show($image, array());

            $message = "[IMAGE][[$image][$postData]]";
            $request->message = $message;

            if ($ConversationID == "-1") {
                return $this->CMessage($request, $token);
            } else
                return $this->SMessage($request, $token);
        }
    }



    public function SocketMessage($Message, $userProfile, $list)
    {
        $messageSocket = array();
        $messageSocket["id"] = $Message->id;
        $messageSocket["message"] = $Message->message;


        $messageSocket["image"] = Cloudder::show($userProfile->profile_picture_small, array());

        $messageSocket["userIDS"] = $userProfile->id;
        $messageSocket["time"] = Carbon::parse(($Message->created_at))->format('h:i a');
        $messageSocket["time_tag"] = Carbon::parse(($Message->created_at))->toFormattedDateString();
        $messageSocket["conversation_id"] = $Message->conversationsV2_id;
        foreach ($list as $item) {
            if ($userProfile->id == $item->user_id) continue;
            event(new \App\Events\MessageV2($item->user_id, $messageSocket));
        }

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

    public function SocketSeen($userId, $userProfile, $list)
    {

        $messageSocket = array();
        $otherId = null;
        $online = $userProfile->last_seen;
        $carbon1 = Carbon::parse($online)->diffForHumans(null, true);
        if (Carbon::now()->diffInSeconds(Carbon::parse($online)) < 61) {
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

    public function SocketChatOpen(Request $request, $token)
    {

    }

    public function UpdateMessageStatus($userID, $ConversationID, $lastDelivered, $lastRead)
    {

        GroupList::where([
            'user_id' => $userID,
            'conversationsV2_id' => $ConversationID,
        ])
//            ->where('lastread' ,'<',$lastRead)

            ->update([
                'lastread' => $lastRead,
                'lastseen' => $lastDelivered
            ]);
    }

    public function UpdateMessageStatus_Delivered($userID, $ConversationID, $lastDelivered)
    {

        GroupList::where([
            'conversationsV2_id' => $ConversationID,
            'user_id' => $userID,
        ])
//          ->where('lastseen' ,'<',$lastDelivered)
            ->update(['lastseen' => $lastDelivered]);
    }

}
