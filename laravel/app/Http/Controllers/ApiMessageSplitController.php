<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\OnlineConversation;
use App\Events\TypingV2;
use App\GroupList;
use App\Message;
use App\MessageV2;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use DB;


class ApiMessageSplitController extends Controller
{


    function cmp($a, $b)
    {
        return strcmp($b['time'], $a['time']);
    }

    public function MessagesList(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $result = DB::select("SELECT *, messagesV2.created_at as 'conversation_time',
                    (select count(messagesV2.id) from messagesV2
                     left OUTER  JOIN group_lists
                     on group_lists.conversationsV2_id = messagesV2.conversationsV2_id
                     where  group_lists.user_id = ?
                     and group_lists.conversationsV2_id = gl.conversationsV2_id
                     and messagesV2.id > group_lists.lastread)
                      as countM, 
                    users.id as uid FROM group_lists as gl
                    left OUTER  JOIN  conversationsV2 as conv
                    on gl.conversationsV2_id = conv.id
                    left OUTER  JOIN  messagesV2
                    on gl.conversationsV2_id = messagesV2.conversationsV2_id
                    left OUTER JOIN users ON 
                    gl.user_id = users.id
                    where gl.conversationsV2_id
                    in (select conversationsV2_id from  group_lists where user_id =?)
                    and messagesV2.id = ( select max(id) from messagesV2 where conversationsV2_id = conv.id)
                    and gl.user_id != ?
                    and messagesV2.id > gl.lastDeleted
                    and gl.auth >0
                    GROUP BY messagesV2.conversationsV2_id
                    order by conversation_time desc
                    ",
                [$userID, $userID, $userID,]);

            $chatList = array();

            foreach ($result as $res) {
                $chat = array();

                if ($res->type == 1 || $res->type == 2) {

                    $chat["name"] = $res->name;
                    $chat["image"] = Cloudder::show($res->profile_picture_small, array());

                    $chat["id"] = $res->id;
                    $chat["userID"] = $res->uid;
                    $chat["count"] = $res->countM;
                    $chat["auth"] = $res->auth;
                    $chat["type"] = $res->type;
                    $chat["conversation_id"] = $res->conversationsV2_id;
                    $chat["time"] = Carbon::parse(($res->conversation_time))->format('h:i a');
                    $chat["time_tag"] = Carbon::parse(($res->conversation_time))->toFormattedDateString();
                    $chat["message"] = $res->message;
                }
                array_push($chatList, $chat);

            }


            return $chatList;
        }
    }

    public function chatsList(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $user->id = $user->user_id;
            $conversations = Conversation:: where('user_id1', $user->id)
                ->orwhere('user_id2', $user->id)
                ->latest()
                ->get();

            $messagesList = array();
            foreach ($conversations as $conversation) {

                $auth = '';

                if ($conversation->user_id1 == $user->id) {
                    $auth = $conversation->status_1;
                } elseif ($conversation->user_id2 == $user->id) {
                    $auth = $conversation->status_2;
                }

                if ($auth == 2 || $auth == 1) {

                    $messages = array();
                    if ($conversation->user_id1 != $user->id) {
                        if ($conversation->type == 2) {
                            $messages["image"] = Cloudder::show('fabits/anonymous-small', array());
                            $messages["name"] = 'Anonymous - ' . $conversation->id;
                            $messages["username"] = '#';
                        } else {
                            $messages["name"] = $conversation->userFrom->name;
                            $messages["username"] = $conversation->userFrom->username;
                            $messages["image"] = Cloudder::show($conversation->userFrom->profile_picture_small, array());
                        }

                    } else {

                        if ($conversation->type == 2) {
                            $messages["name"] = $conversation->userTo->name . ' - Anonymous';
                        } else {
                            $messages["name"] = $conversation->userTo->name;
                        }
                        $messages["username"] = $conversation->userTo->username;
                        $messages["image"] = Cloudder::show($conversation->userTo->profile_picture_small, array());


                    }

                    $messageCount = Message::where('conversation_id', $conversation->id)
                        ->where('status', '<=', 1)
                        ->where('user_id', '!=', $user->id)
                        ->count();
                    $messages["count"] = $messageCount;

                    $message = null;

                    $message = Message::where('conversation_id', $conversation->id)->latest()->first();

                    $messages["auth"] = $auth;
                    $messages["conversation_id"] = $conversation->id;
                    if (count($message) == 0) {
                        $messages["time"] = Carbon::parse(($conversation->created_at))->toDateTimeString();
//                    $messages["time"] = ($conversation->created_at)->date;
                        $messages["time_tag"] = Carbon::parse(($conversation->created_at))->toFormattedDateString();


                    } else {

                        $messages["time"] = Carbon::parse(($message->created_at))->toDateTimeString();
//                    $messages["time"] = ($message->created_at)->date;
                        $messages["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();

                    }
                    if ($auth == 2) {
                        if (count($message) == 0) {
                            $messages["message"] = '';
                        } else {
                            $messages["message"] = $message->message;
                        }
                    } else
                        $messages["message"] = '';

                    if ($auth != -1)
                        array_push($messagesList, $messages);
                }

            }

            $messagesList1 = array();

            usort($messagesList, array($this, "cmp"));


            $i = 0;
            foreach ($messagesList as $ml) {

                $time = Carbon::now()->diffInDays(Carbon::parse($messagesList[$i]["time"]));
                if ($time >= 1) {

                    $messagesList[$i]["time"] = Carbon::parse(($messagesList[$i]["time"]))->format('j-m-y');


                } else {

                    $messagesList[$i]["time"] = Carbon::parse(($messagesList[$i]["time"]))->format('h:i a');
                }

                $i++;
            }

            return $messagesList;


//            return view('home.messages')
//                ->with('ajax', $ajax)
//                ->with('conversations', $messagesList);
        } else {

            return -1;
        }
    }

    public function typing(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $conversationId = $request->conversationId;
            $messageSocket = array();

            $list = GroupList::where('conversationsV2_id', $conversationId)->where('user_id', '!=', $userID)->get();

            $userProfile = Sentinel::findById($userID);

            $messageSocket["typing"] = "Typing...";
            $messageSocket["userID"] = $userID;
            $messageSocket["image"] = Cloudder::show($userProfile->profile_picture_small, array());

            foreach ($list as $item) {
                event(new TypingV2($item->user_id, $messageSocket, $conversationId));
            }

        }

    }

    public function chatAllow(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $ConversationID = $request->conversation_Id;

            GroupList::where('user_id', '!=', $userID)
                ->where('conversationsV2_id', $ConversationID)
                ->update([
                    'auth' => '2',
                ]);
        }
    }

    public function chatBlock(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $ConversationID = $request->conversation_Id;

            GroupList::where('user_id', '!=', $userID)
                ->where('conversationsV2_id', $ConversationID)
                ->update([
                    'auth' => '0',
                ]);


        }


    }

    public function online_conversation(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $conversationId = $request->conversationId;
            $type = $request->type;

            $list = GroupList::where('conversationsV2_id', $conversationId)->where('user_id', '!=', $userID)->get();

            $messageSocket = array();
            $messageSocket["type"] = $type;
            $messageSocket["conversation_id"] = $conversationId;

            foreach ($list as $item) {
                event(new OnlineConversation($item->user_id, $messageSocket));
            }

        }

    }

    public function conversationDelete(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $conversationId = $request->conversation_Id;

            $res = MessageV2:: where('conversationsV2_id', $conversationId)
                ->orderBy('id', 'DESC')->first();


            GroupList::where('user_id', '!=', $userID)
                ->where('conversationsV2_id', $conversationId)
                ->update([
                    'lastDeleted' => $res->id,
                ]);
        }

    }



}
