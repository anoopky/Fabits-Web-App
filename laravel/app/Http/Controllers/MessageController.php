<?php
//
//namespace App\Http\Controllers;
//
//use App\Conversation;
//use App\Events\ChatMessages;
//use App\Events\Chatting;
//use App\Events\Typing;
//use App\Message;
//use Carbon\Carbon;
//use Cloudder;
//use Illuminate\Http\Request;
//use Sentinel;
//
//
//class MessageController extends Controller
//{
//
//    public function index(Request $request)
//    {
//        $user = Sentinel::check();
//        $conversations = Conversation:: where('user_id1', $user->id)
//            ->orwhere('user_id2', $user->id)
//            ->latest()
//            ->get();
//
//        $messagesList = array();
//        foreach ($conversations as $conversation) {
//
//            $auth = '';
//
//            if ($conversation->user_id1 == $user->id) {
//                $auth = $conversation->status_1;
//            } elseif ($conversation->user_id2 == $user->id) {
//                $auth = $conversation->status_2;
//            }
//
//            if ($auth == 2 || $auth == 1) {
//
//                $messages = array();
//                if ($conversation->user_id1 != $user->id) {
//                    if ($conversation->type == 2) {
//                        $messages["image"] = Cloudder::show('fabits/anonymous-small', array());
//                        $messages["name"] = 'Anonymous - ' . $conversation->id;
//                        $messages["username"] = '#';
//                    } else {
//                        $messages["name"] = $conversation->userFrom->name;
//                        $messages["username"] = $conversation->userFrom->username;
//                        $messages["image"] = Cloudder::show($conversation->userFrom->profile_picture_small, array());
//                    }
//
//                } else {
//
//                    if ($conversation->type == 2) {
//                        $messages["name"] = $conversation->userTo->name . ' - Anonymous';
//                    } else {
//                        $messages["name"] = $conversation->userTo->name;
//                    }
//                    $messages["username"] = $conversation->userTo->username;
//                    $messages["image"] = Cloudder::show($conversation->userTo->profile_picture_small, array());
//
//
//                }
//                $message = null;
//                $message = Message::where('conversation_id', $conversation->id)->latest()->first();
//
//                $messageCount = Message::where('conversation_id', $conversation->id)
//                    ->where('status', '<=', 1)
//                    ->where('user_id', '!=', $user->id)
//                    ->count();
//                $messages["count"] = $messageCount;
//
//
//                $messages["auth"] = $auth;
//                $messages["conversation_id"] = $conversation->id;
//                if (empty($message)) {
//
//                    $messages["time_tag"] = Carbon::parse(($conversation->created_at))->toFormattedDateString();
//
//
//                } else {
//
//                    $messages["time"] = Carbon::parse(($message->created_at))->format('h:i a');
//                    $messages["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();
//
//                }
//                if ($auth == 2) {
//                    if (empty($message)) {
//                        $messages["message"] = '';
//                    } else {
//                        $messages["message"] = $message->message;
//                    }
//                } else
//                    $messages["message"] = '';
//
//                if ($auth != -1)
//                    array_push($messagesList, $messages);
//            }
//
//        }
//
//        $ajax = false;
//        if ($request->ajax()) {
//            $messagesList1[0] = "";
//            $messagesList1[1] = $messagesList;
//            return $messagesList1;
//        } else
//            return view('home.messages')
//                ->with('ajax', $ajax)
//                ->with('conversations', $messagesList);
//
//    }
//
//    public function index1(Request $request)
//    {
//        $user = Sentinel::check();
//        $conversations = Conversation:: where('user_id1', $user->id)
//            ->orwhere('user_id2', $user->id)
//            ->latest()
//            ->get();
//
//        $messagesList = array();
//        foreach ($conversations as $conversation) {
//
//            $auth = '';
//
//            if ($conversation->user_id1 == $user->id) {
//                $auth = $conversation->status_1;
//            } elseif ($conversation->user_id2 == $user->id) {
//                $auth = $conversation->status_2;
//            }
//
//            if ($auth == 2 || $auth == 1) {
//
//                $messages = array();
//
//                if ($conversation->user_id1 != $user->id) {
//
//                    if ($conversation->type == 2) {
//                        $messages["image"] = Cloudder::show('fabits/anonymous-small', array());
//                        $messages["name"] = 'Anonymous - ' . $conversation->id;
//                        $messages["username"] = '#';
//
//
//                    } else {
//
//                        $messages["name"] = $conversation->userFrom->name;
//                        $messages["username"] = $conversation->userFrom->username;
//                        $messages["image"] = Cloudder::show($conversation->userFrom->profile_picture_small, array());
//                    }
//
//
//                } else {
//
//                    if ($conversation->type == 2) {
//                        $messages["name"] = $conversation->userTo->name . ' - Anonymous';
//                    } else {
//                        $messages["name"] = $conversation->userTo->name;
//                    }
//                    $messages["username"] = $conversation->userTo->username;
//                    $messages["image"] = Cloudder::show($conversation->userTo->profile_picture_small, array());
//
//
//                }
//                $message = null;
//
//                $message = Message::where('conversation_id', $conversation->id)->latest()->first();
//                $messageCount = Message::where('conversation_id', $conversation->id)
//                    ->where('status', '<=', 1)
//                    ->where('user_id', '!=', $user->id)
//                    ->count();
//                $messages["count"] = $messageCount;
//
//
//                $messages["auth"] = $auth;
//                $messages["conversation_id"] = $conversation->id;
//                if (empty($message)) {
//                    $messages["time"] = Carbon::parse(($conversation->created_at))->format('h:i a');
//                    $messages["time_tag"] = Carbon::parse(($conversation->created_at))->toFormattedDateString();
//
//
//                } else {
//                    $messages["time"] = Carbon::parse(($message->created_at))->format('h:i a');
//                    $messages["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();
//
//                }
//                if ($auth == 2) {
//                    if (empty($message)) {
//                        $messages["message"] = '';
//                    } else {
//                        $messages["message"] = $this->textLimit($message->message, 15);
//                    }
//                } else
//                    $messages["message"] = '';
//
//
//                if ($auth != -1)
//                    array_push($messagesList, $messages);
//            }
//
//        }
//
//        $ajax = false;
//        if ($request->ajax()) {
//
//            $ajax = true;
//        }
//
//        return view('home.messages')
//            ->with('ajax', $ajax)
//            ->with('conversations', $messagesList);
//
//    }
//
//    public function save(Request $request)
//    {
//
//        $this->validate($request, [
//            'message' => 'required',
//        ],
//            [
//                'message.*' => 'Message can\'t be blank!',
//            ]);
//
//        $user = Sentinel::check();
//        $conversation_id = $request->conversation_id;
//        $message = $request->message;
//
//        $conversation = Conversation::where([
//            ['id', $conversation_id],
//        ])->first();
//
//        $auth = '';
//        $auth_other = '';
//
//        $Status_auth = '';
//        $Status_auth_other = '';
//
//        if ($conversation->user_id1 == $user->id) {
//            $auth = $conversation->status_1;
//            $auth_other = $conversation->status_2;
//            $Status_auth = 'status_1';
//            $Status_auth_other = 'status_2';
//
//        } elseif ($conversation->user_id2 == $user->id) {
//            $auth = $conversation->status_2;
//            $auth_other = $conversation->status_1;
//            $Status_auth = 'status_2';
//            $Status_auth_other = 'status_1';
//        }
//        if (strlen($Status_auth) > 2) {
//            if ($auth_other == 0) {
//
//                $conversation->update([
//                    $Status_auth => 2,
//                    $Status_auth_other => 1,
//                ]);
//            }
//
//            if ($auth == 1) {
//
//                $conversation->update([
//                    $Status_auth => 2,
//                ]);
//
//            }
//
//            $Message = Message::create([
//
//                'conversation_id' => $conversation_id,
//                'message' => $message,
//                'user_id' => $user->id,
//                'status' => 0,
//
//            ]);
//
//            $data["id"] = $Message->id;
//            $data["message"] = $message;
//            $data["status"] = 'S';
//            $data["time"] = Carbon::parse(($Message->created_at))->format('h:i a');
//            $data["time_tag"] = Carbon::parse(($Message->created_at))->toFormattedDateString();
//
//
//            $TellUser = null;
//            if ($conversation->user_id1 != $user->id)
//                $TellUser = $conversation->user_id1;
//            else
//                $TellUser = $conversation->user_id2;
//
//            event(new Chatting($TellUser));
//            return $data;
//        }
//        return '[]';
//    }
//
//    public function typing(Request $request)
//    {
//
//        $this->validate($request, [
//            'id' => 'bail|required|integer',
//        ],
//            [
//                'id.*' => 'Invalid request!!',
//            ]);
//
//        $user = Sentinel::check();
//
//        $conversation = Conversation::where([
//            ['id', $request->id],
//        ])
//            ->first();
//
//        $TellUser = null;
//        if ($conversation->user_id1 != $user->id)
//            $TellUser = $conversation->user_id1;
//        else if ($conversation->user_id2 != $user->id)
//            $TellUser = $conversation->user_id2;
//
//        if ($TellUser != null)
//            event(new Typing($TellUser, $request->id));
//
//        return null;
//
//    }
//
//    public function check(Request $request, $id)
//    {
//
//        $Message = Message::where('id', $id)->first();
//
//        $status = 'S';
//
//        if ($Message->status == 0)
//            $status = 'S';
//
//        elseif ($Message->status == 1)
//            $status = 'D';
//
//        elseif ($Message->status == 2)
//            $status = 'R';
//
//        $data["status"] = $status;
//
//        return $data;
//
//    }
//
//    public function checkPerson(Request $request, $id)
//    {
//
//        $user = Sentinel::check();
//
//
//        $Message = Conversation::where('id', $id)->first();
//
//        $target_user = '';
//        if ($Message->user_id1 != $user->id) {
//            $target_user = $Message->user_id1;
//
//        } else if ($Message->user_id2 != $user->id) {
//
//            $target_user = $Message->user_id2;
//        }
//
//
//        $T_user = Sentinel::findById($target_user);
//
//        $time = $this->datesmall(Carbon::parse(($T_user->last_seen))->diffForHumans());
//
//        if (Carbon::now()->diffInSeconds(Carbon::parse($T_user->last_seen)) < 31)
//            return 'Online';
//        else
//            return $time;
//
//    }
//
//    public function seen(Request $request)
//    {
//        $this->validate($request, [
//            'id' => 'bail|required|integer',
//        ],
//            [
//                'id.*' => 'Invalid request!!',
//            ]);
//
//        $user = Sentinel::check();
//
//        $TellUser = null;
//        $allowed = 0;
//        $Message = Message::where('id', $request->id)->first();
//
//
//        $conversation = Conversation::where([
//            ['id', $Message->conversation_id],
//        ])->first();
//
//        if ($conversation->user_id1 == $user->id) {
//            $TellUser = $conversation->user_id2;
//            $allowed = 1;
//
//        } else if ($conversation->user_id2 == $user->id) {
//            $TellUser = $conversation->user_id1;
//            $allowed = 1;
//
//        }
//        if ($allowed) {
//
//            $Message->update(['status' => 2]);
//
//
//            event(new ChatMessages($TellUser));
//        }
//        return 'true';
//
//    }
//
//    public function blockedChat(Request $request)
//    {
//        $this->validate($request, [
//            'id' => 'bail|required|integer',
//        ],
//            [
//                'id.*' => 'Invalid request!!',
//            ]);
//
//        $user = Sentinel::check();
//
//        $conversation = Conversation::where([
//            ['id', $request->id],
//        ])->first();
//
//        if ($conversation->user_id1 == $user->id) {
//            $conversation->update(['status_1' => 0]);
//
//        } elseif ($conversation->user_id2 == $user->id) {
//            $conversation->update(['status_2' => 0]);
//        }
//
//        return 'true';
//
//    }
//
//    public function allow(Request $request)
//    {
//        $this->validate($request, [
//            'id' => 'bail|required|integer',
//        ],
//            [
//                'id.*' => 'Invalid request!!',
//            ]);
//
//        $user = Sentinel::check();
//
//
//        $conversation = Conversation::where([
//            ['id', $request->id],
//        ])->first();
//
//        $Status_auth = '';
//
//        if ($conversation->user_id1 == $user->id) {
//            $Status_auth = 'status_1';
//
//        } elseif ($conversation->user_id2 == $user->id) {
//            $Status_auth = 'status_2';
//        }
//
//        if (strlen($Status_auth) > 2) {
//            $conversation->update([$Status_auth => 2]);
//            return 'true';
//
//        } else
//            return 'false';
//
//    }
//
//    public function block(Request $request)
//    {
//        $this->validate($request, [
//            'id' => 'bail|required|integer',
//        ],
//            [
//                'id.*' => 'Invalid request!!',
//            ]);
//
//        $user = Sentinel::check();
//
//
//        $conversation = Conversation::where([
//            ['id', $request->id],
//        ])->first();
//
//        $Status_auth = '';
//
//        if ($conversation->user_id1 == $user->id) {
//
//            $Status_auth = 'status_1';
//
//
//        } elseif ($conversation->user_id2 == $user->id) {
//
//            $Status_auth = 'status_2';
//
//        }
//
//        if (strlen($Status_auth) > 2) {
//            $conversation->update([$Status_auth => -1]);
//            return 'true';
//
//        } else
//            return 'false';
//
//    }
//
//    public function seen_all(Request $request)
//    {
//
//        $this->validate($request, [
//            'conversation_id' => 'bail|required|integer',
//        ],
//            [
//                'conversation_id.*' => 'Invalid request!!',
//            ]);
//
//
//        $user = Sentinel::check();
//
//        $conversation = Conversation::where([
//            ['id', $request->conversation_id],
//        ])->first();
//
//        $auth = '';
//
//        if ($conversation->user_id1 == $user->id) {
//            $auth = $conversation->status_1;
//        } elseif ($conversation->user_id2 == $user->id) {
//            $auth = $conversation->status_2;
//        }
//
//        if ($auth == 2) {
//
//            Message::where('conversation_id', $request->conversation_id)
//                ->where('user_id', '!=', $user->id)
//                ->update(['status' => 2]);
//
//            $TellUser = null;
//
//
//            if ($conversation->user_id1 == $user->id) {
//                $TellUser = $conversation->user_id2;
//            } elseif ($conversation->user_id2 == $user->id) {
//                $TellUser = $conversation->user_id1;
//            }
//            event(new ChatMessages($TellUser));
//            return 'true';
//
//        }
//        return 'false';
//
//    }
//
//    public function show(Request $request)
//    {
//
//        $user = Sentinel::check();
//        $messageData = array();
//        $conversations = Conversation::where('user_id1', $user->id)
//            ->orwhere('user_id2', $user->id)
//            ->get();
//
//        foreach ($conversations as $conversation) {
//            $auth = '';
//
//            if ($conversation->user_id1 == $user->id) {
//                $auth = $conversation->status_1;
//            } elseif ($conversation->user_id2 == $user->id) {
//                $auth = $conversation->status_2;
//            }
//
//            if ($auth != -1) {
//
//                $messages = Message::where('conversation_id', $conversation->id)
//                    ->where('user_id', '!=', $user->id)
//                    ->where('status', 0)
//                    ->orderby('created_at')
//                    ->select(['id', 'message', 'conversation_id', 'user_id'])
//                    ->get();
//
//                $messagelist1 = array();
//                foreach ($messages as $message) {
//                    $messagelist = array();
//
//                    $messagelist["status"] = '';
//                    if ($auth == 2) {
//                        $messagelist["message"] = $message["message"];
//                        $messagelist["id"] = $message->id;
//                        $message->update(['status' => 1]);
//                    } else {
//
//                        $messagelist["message"] = '';
//                        $messagelist["id"] = -1;
//
//                    }
//                    $messagelist["auth"] = $auth;
//                    $messagelist["conversation_id"] = $message->conversation_id;
//
//
//                    $messagelist["type"] = $conversation->type;
//
//                    if ($conversation->type == 2) {
//
//                        $messagelist["user_picture"] = Cloudder::show('fabits/anonymous-small', array());
//                        $messagelist["user_name"] = 'Anonymous - ' . $conversation->id;
//                        $messagelist["username"] = '#';
//
//
//                    } else {
//                        $messagelist["user_picture"] = Cloudder::show($message->user->profile_picture_small, array());
//                        $messagelist["user_name"] = $message->user->name;
//                        $messagelist["username"] = $message->user->username;
//                    }
//
//                    $messagelist["time"] = Carbon::parse(($message->created_at))->format('h:i a');
//                    $messagelist["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();
//                    array_push($messagelist1, $messagelist);
//                }
//                $TellUser = null;
//
//                if ($conversation->user_id1 != $user->id)
//                    $TellUser = $conversation->user_id1;
//                else
//                    $TellUser = $conversation->user_id2;
//
//                event(new ChatMessages($TellUser));
//                if (count($messagelist1))
//                    array_push($messageData, $messagelist1);
//
//            }
//        }
//        return $messageData;
//
//    }
//
//    public function create(Request $request)
//    {
//
//        $this->validate($request, [
//            'user_id' => 'bail|required|integer|exists:users,id',
//        ],
//            [
//                'user_id.*' => 'Invalid request!!',
//
//            ]);
//
//
//        $user = Sentinel::check();
//
//        $type = 1;
//
//        if ($request->chat_a == "true")
//            $type = 2;
//
//        $conversation = null;
//        if ($type == 1)
//            $conversation = Conversation::where([
//                ['user_id1', $user->id],
//                ['user_id2', $request->user_id],
//                ['type', $type],
//            ])->orwhere([
//                ['user_id2', $user->id],
//                ['user_id1', $request->user_id],
//                ['type', $type],
//            ])->first();
//
//
//        elseif ($type == 2)
//            $conversation = Conversation::where([
//                ['user_id1', $user->id],
//                ['user_id2', $request->user_id],
//                ['type', $type],
//            ])->first();
//
//
//        if (count($conversation)) {
//
//            $user1 = Sentinel::findById($request->user_id);
//            $data[0] = "";
//            $data[1] = $conversation->id;
//            if ($conversation->type == 2) {
//                $data[2] = $user1->name . ' - Anonymous';
//
//            } elseif ($conversation->type == 1) {
//                $data[2] = $user1->name;
//            }
//            $data[3] = Cloudder::show($user1->profile_picture_small, array());
//            $data[4] = $user1->username;
//
//            $auth = '';
//
//            $Status_auth = '';
//
//            if ($conversation->user_id1 == $user->id) {
//                $auth = $conversation->status_1;
//                $Status_auth = 'status_1';
//            } elseif ($conversation->user_id2 == $user->id) {
//                $auth = $conversation->status_2;
//                $Status_auth = 'status_2';
//            }
//
//            $data[5] = $auth;
//
//            if ($auth == 0) {
//                $conversation->update([$Status_auth => 2]);
//            }
//
//            return $data;
//
//        } else {
//
//            $conversation = Conversation::create([
//                'user_id1' => $user->id,
//                'user_id2' => $request->user_id,
//                'type' => $type,
//                'status_1' => 2,
//                'status_2' => 0,
//
//            ]);
//
//            $user1 = Sentinel::findById($request->user_id);
//            $data[0] = "";
//            $data[1] = $conversation->id;
//            if ($type == 2) {
//                $data[2] = $user1->name . ' - Anonymous';
//            } elseif ($type == 1) {
//                $data[2] = $user1->name;
//            }
//            $data[3] = Cloudder::show($user1->profile_picture_small, array());
//            $data[4] = $user1->username;
//            $auth = '';
//
//            if ($conversation->user_id1 == $user->id) {
//                $auth = $conversation->status_1;
//            } elseif ($conversation->user_id2 == $user->id) {
//                $auth = $conversation->status_2;
//            }
//
//            $data[5] = $auth;
//
//            return $data;
//
//        }
//
//    }
//
//    public function load_prev(Request $request)
//    {
//        $this->validate($request, [
//            'id' => 'bail|required|integer',
//            'load' => 'bail|required|integer',
//        ],
//            [
//                'id.*' => 'Invalid request!!',
//                'load.*' => 'Invalid request!!',
//
//            ]);
//
//        $user = Sentinel::check();
//        $messageData = array();
//        $conversation_id = $request->id;
//        $load = $request->load;
//        $offset = ($load * 10);
//
//        $conversations = Conversation::where('id', $conversation_id)
//            ->where('user_id1', $user->id)
//            ->orwhere('user_id2', $user->id)
//            ->first();
//
//        if (count($conversations)) {
//            $messages = Message::where('conversation_id', $conversation_id)
//                ->orderby('id', 'desc')
//                ->select(['id', 'message', 'conversation_id', 'user_id', 'status', 'created_at'])
//                ->offset($offset)
//                ->limit(10)
//                ->get();
//
//            foreach ($messages as $message) {
//                $messagelist = array();
//
//                if ($message->user_id == $user->id) {
//                    $messagelist["me"] = '0';
//                } else {
//                    $messagelist["me"] = '1';
//                }
//
//                $status = 'S';
//
//                if ($message->status == 0)
//                    $status = 'S';
//
//                elseif ($message->status == 1)
//                    $status = 'D';
//
//                elseif ($message->status == 2)
//                    $status = 'R';
//                if ($message->user_id == $user->id) {
//                    $messagelist["status"] = $status;
//                } else {
//
//                    $messagelist["status"] = '';
//
//                }
//                $messagelist["message"] = $message["message"];
//                $messagelist["id"] = $message->id;
//
//
//                $messagelist["time"] = Carbon::parse(($message->created_at))->format('h:i a');
//                $messagelist["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();
//
//
//                array_push($messageData, $messagelist);
//
//            }
//            $messageData = array_reverse($messageData);
//            return $messageData;
//        }
//    }
//
//    public function chatSession(Request $request)
//    {
//
//        $x = $request->X;
//        $y = $request->Y;
//        $picture = $request->picture;
//        $id = $request->id;
//        $name = $request->name;
//        $href = $request->href;
//        $auth = $request->auth;
//
//        $update = $request->update;
//        $allChatSessions = $request->session()->get('chatSession');
//
//        if (!$allChatSessions)
//            $allChatSessions = array();
//
//        $chatloc = [
//            'X' => $x,
//            'Y' => $y,
//            'id' => $id,
//            'picture' => $picture,
//            'name' => $name,
//            'href' => $href,
//            'auth' => $auth,
//
//        ];
//
//
//        if ($update == 0) {
//
//
//            $request->session()->push('chatSession', $chatloc);
//
//            return $request->session()->get('chatSession');
//        } else {
//            $i = 0;
//
//            foreach ($allChatSessions as $allChatSession) {
//
//                if ($allChatSession["id"] == $id) {
//                    if ($update == -1) {
//
//                        unset($allChatSessions[$i]);
//                        $request->session()->forget('chatSession');
//
//                        foreach ($allChatSessions as $allChatSession2) {
//                            $request->session()->push('chatSession', $allChatSession2);
//                        }
//
//                    } else {
//                        unset($allChatSessions[$i]);
//
//                        $request->session()->forget('chatSession');
//
//                        $request->session()->push('chatSession', $chatloc);
//                        foreach ($allChatSessions as $allChatSession2) {
//                            $request->session()->push('chatSession', $allChatSession2);
//                        }
//
//
//                    }
//
//                    break;
//
//
//                }
//                $i++;
//            }
//
//            return $request->session()->get('chatSession');
//        }
//
//
//    }
//
//    public function chatSessions(Request $request)
//    {
//
//        return $request->session()->get('chatSession');
//
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
//    public function textLimit($text, $len)
//    {
//
//        if (strlen($text) > $len) {
//            return substr($text, 0, $len) . "...";
//        } else
//            return $text;
//
//    }
//
//}


namespace App\Http\Controllers;

use App\Conversation;
use App\Events\ChatMessages;
use App\Events\Chatting;
use App\Events\Typing;
use App\Message;
use Carbon\Carbon;
use Cloudder;
use Illuminate\Http\Request;
use Sentinel;


class MessageController extends Controller
{

    public function index(Request $request)
    {
        $user = Sentinel::check();
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

        usort($messagesList, array( $this, "cmp"));


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


        if ($request->ajax()) {
            $messagesList1[0] = "";
            $messagesList1[1] = $messagesList;
            return $messagesList1;

        } else
            return '-1';
//            return view('home.messages')
//                ->with('ajax', $ajax)
//                ->with('conversations', $messagesList);

    }

    function cmp($a, $b){
        return strcmp($b['time'], $a['time']);
    }

    public function index1(Request $request)
    {
        $user = Sentinel::check();
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
                        $messages["message"] = $this->textLimit($message->message, 15);
                    }
                } else
                    $messages["message"] = '';

                if ($auth != -1)
                    array_push($messagesList, $messages);
            }

        }


        $ajax = false;
        if ($request->ajax()) {

            $ajax = true;
        }

        usort($messagesList, array( $this, "cmp"));

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

//        return $messagesList;
        return view('home.messages')
            ->with('ajax', $ajax)
            ->with('conversations', $messagesList);

    }


    public function save(Request $request)
    {

        $this->validate($request, [
            'message' => 'required',
        ],
            [
                'message.*' => 'Message can\'t be blank!',
            ]);

        $user = Sentinel::check();
        $conversation_id = $request->conversation_id;
        $message = $request->message;

        $conversation = Conversation::where([
            ['id', $conversation_id],
        ])->first();

        $auth = '';
        $auth_other = '';

        $Status_auth = '';
        $Status_auth_other = '';

        if ($conversation->user_id1 == $user->id) {
            $auth = $conversation->status_1;
            $auth_other = $conversation->status_2;
            $Status_auth = 'status_1';
            $Status_auth_other = 'status_2';

        } elseif ($conversation->user_id2 == $user->id) {
            $auth = $conversation->status_2;
            $auth_other = $conversation->status_1;
            $Status_auth = 'status_2';
            $Status_auth_other = 'status_1';
        }
        if (strlen($Status_auth) > 2) {
            if ($auth_other == 0) {

                $conversation->update([
                    $Status_auth => 2,
                    $Status_auth_other => 1,
                ]);
            }

            if ($auth == 1) {

                $conversation->update([
                    $Status_auth => 2,
                ]);

            }

            $Message = Message::create([

                'conversation_id' => $conversation_id,
                'message' => $message,
                'user_id' => $user->id,
                'status' => 0,

            ]);

            $data["id"] = $Message->id;
            $data["message"] = $message;
            $data["status"] = 'S';
            $data["time"] = Carbon::parse(($Message->created_at))->format('h:i a');
            $data["time_tag"] = Carbon::parse(($Message->created_at))->toFormattedDateString();


            $TellUser = null;
            if ($conversation->user_id1 != $user->id)
                $TellUser = $conversation->user_id1;
            else
                $TellUser = $conversation->user_id2;

            event(new Chatting($TellUser));
            return $data;
        }
        return '[]';
    }

    public function typing(Request $request)
    {

        $this->validate($request, [
            'id' => 'bail|required|integer',
        ],
            [
                'id.*' => 'Invalid request!!',
            ]);

        $user = Sentinel::check();

        $conversation = Conversation::where([
            ['id', $request->id],
        ])
            ->first();

        $TellUser = null;
        if ($conversation->user_id1 != $user->id)
            $TellUser = $conversation->user_id1;
        else if ($conversation->user_id2 != $user->id)
            $TellUser = $conversation->user_id2;

        if ($TellUser != null)
            event(new Typing($TellUser, $request->id));

        return null;

    }

    public function check(Request $request, $id)
    {

        $Message = Message::where('id', $id)->first();

        $status = 'S';

        if ($Message->status == 0)
            $status = 'S';

        elseif ($Message->status == 1)
            $status = 'D';

        elseif ($Message->status == 2)
            $status = 'R';

        $data["status"] = $status;

        return $data;

    }

    public function checkPerson(Request $request, $id)
    {

        $user = Sentinel::check();


        $Message = Conversation::where('id', $id)->first();

        $target_user = '';
        if ($Message->user_id1 != $user->id) {
            $target_user = $Message->user_id1;

        } else if ($Message->user_id2 != $user->id) {

            $target_user = $Message->user_id2;
        }


        $T_user = Sentinel::findById($target_user);

        $time = $this->datesmall(Carbon::parse(($T_user->last_seen))->diffForHumans());

        if (Carbon::now()->diffInSeconds(Carbon::parse($T_user->last_seen)) < 31)
            return 'Online';
        else
            return $time;

    }

    public function seen(Request $request)
    {
        $this->validate($request, [
            'id' => 'bail|required|integer',
        ],
            [
                'id.*' => 'Invalid request!!',
            ]);

        $user = Sentinel::check();

        $TellUser = null;
        $allowed = 0;
        $Message = Message::where('id', $request->id)->first();


        $conversation = Conversation::where([
            ['id', $Message->conversation_id],
        ])->first();

        if ($conversation->user_id1 == $user->id) {
            $TellUser = $conversation->user_id2;
            $allowed = 1;

        } else if ($conversation->user_id2 == $user->id) {
            $TellUser = $conversation->user_id1;
            $allowed = 1;

        }
        if ($allowed) {

            $Message->update(['status' => 2]);


            event(new ChatMessages($TellUser));
        }
        return 'true';

    }

    public function blockedChat(Request $request)
    {
        $this->validate($request, [
            'id' => 'bail|required|integer',
        ],
            [
                'id.*' => 'Invalid request!!',
            ]);

        $user = Sentinel::check();

        $conversation = Conversation::where([
            ['id', $request->id],
        ])->first();

        if ($conversation->user_id1 == $user->id) {
            $conversation->update(['status_1' => 0]);

        } elseif ($conversation->user_id2 == $user->id) {
            $conversation->update(['status_2' => 0]);
        }

        return 'true';

    }

    public function allow(Request $request)
    {
        $this->validate($request, [
            'id' => 'bail|required|integer',
        ],
            [
                'id.*' => 'Invalid request!!',
            ]);

        $user = Sentinel::check();


        $conversation = Conversation::where([
            ['id', $request->id],
        ])->first();

        $Status_auth = '';

        if ($conversation->user_id1 == $user->id) {
            $Status_auth = 'status_1';

        } elseif ($conversation->user_id2 == $user->id) {
            $Status_auth = 'status_2';
        }

        if (strlen($Status_auth) > 2) {
            $conversation->update([$Status_auth => 2]);
            return 'true';

        } else
            return 'false';

    }

    public function block(Request $request)
    {
        $this->validate($request, [
            'id' => 'bail|required|integer',
        ],
            [
                'id.*' => 'Invalid request!!',
            ]);

        $user = Sentinel::check();


        $conversation = Conversation::where([
            ['id', $request->id],
        ])->first();

        $Status_auth = '';

        if ($conversation->user_id1 == $user->id) {

            $Status_auth = 'status_1';


        } elseif ($conversation->user_id2 == $user->id) {

            $Status_auth = 'status_2';

        }

        if (strlen($Status_auth) > 2) {
            $conversation->update([$Status_auth => -1]);
            return 'true';

        } else
            return 'false';

    }

    public function seen_all(Request $request)
    {

        $this->validate($request, [
            'conversation_id' => 'bail|required|integer',
        ],
            [
                'conversation_id.*' => 'Invalid request!!',
            ]);


        $user = Sentinel::check();

        $conversation = Conversation::where([
            ['id', $request->conversation_id],
        ])->first();

        $auth = '';

        if ($conversation->user_id1 == $user->id) {
            $auth = $conversation->status_1;
        } elseif ($conversation->user_id2 == $user->id) {
            $auth = $conversation->status_2;
        }

        if ($auth == 2) {

            Message::where('conversation_id', $request->conversation_id)
                ->where('user_id', '!=', $user->id)
                ->update(['status' => 2]);

            $TellUser = null;


            if ($conversation->user_id1 == $user->id) {
                $TellUser = $conversation->user_id2;
            } elseif ($conversation->user_id2 == $user->id) {
                $TellUser = $conversation->user_id1;
            }
            event(new ChatMessages($TellUser));
            return 'true';

        }
        return 'false';

    }

    public function show(Request $request)
    {

        $user = Sentinel::check();
        $messageData = array();
        $conversations = Conversation::where('user_id1', $user->id)
            ->orwhere('user_id2', $user->id)
            ->where([
                ['status_1', '!=', '2'],
                ['status_2', '!=', '0'],
            ])
            ->get();

        foreach ($conversations as $conversation) {
            $auth = '';

            if ($conversation->user_id1 == $user->id) {
                $auth = $conversation->status_1;
            } elseif ($conversation->user_id2 == $user->id) {
                $auth = $conversation->status_2;
            }

            if ($auth != -1) {

                $messages = Message::where('conversation_id', $conversation->id)
                    ->where('user_id', '!=', $user->id)
                    ->where('status', 0)
                    ->orderby('created_at')
                    ->select(['id', 'message', 'conversation_id', 'user_id'])
                    ->get();

                if(count($messages)>0) {
                    $messagelist1 = array();

                    foreach ($messages as $message) {
                        $messagelist = array();

                        $messagelist["status"] = '';
                        if ($auth == 2) {
                            $messagelist["message"] = $message["message"];
                            $messagelist["id"] = $message->id;
                            $message->update(['status' => 1]);
                        } else {

                            $messagelist["message"] = '';
                            $messagelist["id"] = -1;

                        }
                        $messagelist["auth"] = $auth;
                        $messagelist["conversation_id"] = $message->conversation_id;


                        $messagelist["type"] = $conversation->type;

                        if ($conversation->type == 2) {

                            $messagelist["user_picture"] = Cloudder::show('fabits/anonymous-small', array());
                            $messagelist["user_name"] = 'Anonymous - ' . $conversation->id;
                            $messagelist["username"] = '#';


                        } else {
                            $messagelist["user_picture"] = Cloudder::show($message->user->profile_picture_small, array());
                            $messagelist["user_name"] = $message->user->name;
                            $messagelist["username"] = $message->user->username;
                        }

                        $messagelist["time"] = Carbon::parse(($message->created_at))->format('h:i a');
                        $messagelist["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();
                        array_push($messagelist1, $messagelist);
                    }
                    $TellUser = null;

                    if ($conversation->user_id1 != $user->id)
                        $TellUser = $conversation->user_id1;
                    else
                        $TellUser = $conversation->user_id2;

                    event(new ChatMessages($TellUser));
                    if (count($messagelist1))
                        array_push($messageData, $messagelist1);
                }
            }
        }
        return $messageData;

    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'bail|required|integer|exists:users,id',
        ],
            [
                'user_id.*' => 'Invalid request!!',

            ]);


        $user = Sentinel::check();

        $type = 1;

        if ($request->chat_a == "true")
            $type = 2;

        $conversation = null;
        if ($type == 1)
            $conversation = Conversation::where([
                ['user_id1', $user->id],
                ['user_id2', $request->user_id],
                ['type', $type],
            ])->orwhere([
                ['user_id2', $user->id],
                ['user_id1', $request->user_id],
                ['type', $type],
            ])->first();


        elseif ($type == 2)
            $conversation = Conversation::where([
                ['user_id1', $user->id],
                ['user_id2', $request->user_id],
                ['type', $type],
            ])->first();


        if (count($conversation)) {

            $user1 = Sentinel::findById($request->user_id);
            $data[0] = "";
            $data[1] = $conversation->id;
            if ($conversation->type == 2) {
                $data[2] = $user1->name . ' - Anonymous';

            } elseif ($conversation->type == 1) {
                $data[2] = $user1->name;
            }
            $data[3] = Cloudder::show($user1->profile_picture_small, array());
            $data[4] = $user1->username;

            $auth = '';

            $Status_auth = '';

            if ($conversation->user_id1 == $user->id) {
                $auth = $conversation->status_1;
                $Status_auth = 'status_1';
            } elseif ($conversation->user_id2 == $user->id) {
                $auth = $conversation->status_2;
                $Status_auth = 'status_2';
            }

            $data[5] = $auth;

            if ($auth == 0) {
                $conversation->update([$Status_auth => 2]);
            }

            return $data;

        } else {

            $conversation = Conversation::create([
                'user_id1' => $user->id,
                'user_id2' => $request->user_id,
                'type' => $type,
                'status_1' => 2,
                'status_2' => 0,

            ]);

            $user1 = Sentinel::findById($request->user_id);
            $data[0] = "";
            $data[1] = $conversation->id;
            if ($type == 2) {
                $data[2] = $user1->name . ' - Anonymous';
            } elseif ($type == 1) {
                $data[2] = $user1->name;
            }
            $data[3] = Cloudder::show($user1->profile_picture_small, array());
            $data[4] = $user1->username;
            $auth = '';

            if ($conversation->user_id1 == $user->id) {
                $auth = $conversation->status_1;
            } elseif ($conversation->user_id2 == $user->id) {
                $auth = $conversation->status_2;
            }

            $data[5] = $auth;

            return $data;

        }

    }

    public function load_prev(Request $request)
    {
        $this->validate($request, [
            'id' => 'bail|required|integer',
            'load' => 'bail|required|integer',
        ],
            [
                'id.*' => 'Invalid request!!',
                'load.*' => 'Invalid request!!',

            ]);

        $user = Sentinel::check();
        $messageData = array();
        $conversation_id = $request->id;
        $load = $request->load;
        $offset = ($load * 10);

        $conversations = Conversation::where('id', $conversation_id)
            ->where('user_id1', $user->id)
            ->orwhere('user_id2', $user->id)
            ->first();

        if (count($conversations)) {
            $messages = Message::where('conversation_id', $conversation_id)
                ->orderby('id', 'desc')
                ->select(['id', 'message', 'conversation_id', 'user_id', 'status', 'created_at'])
                ->offset($offset)
                ->limit(10)
                ->get();

            foreach ($messages as $message) {
                $messagelist = array();

                if ($message->user_id == $user->id) {
                    $messagelist["me"] = '0';
                } else {
                    $messagelist["me"] = '1';
                }

                $status = 'S';

                if ($message->status == 0)
                    $status = 'S';

                elseif ($message->status == 1)
                    $status = 'D';

                elseif ($message->status == 2)
                    $status = 'R';
                if ($message->user_id == $user->id) {
                    $messagelist["status"] = $status;
                } else {

                    $messagelist["status"] = '';

                }
                $messagelist["message"] = $message["message"];
                $messagelist["id"] = $message->id;


                $messagelist["time"] = Carbon::parse(($message->created_at))->format('h:i a');
                $messagelist["time_tag"] = Carbon::parse(($message->created_at))->toFormattedDateString();


                array_push($messageData, $messagelist);

            }
            $messageData = array_reverse($messageData);
            return $messageData;
        }
    }

    public function chatSession(Request $request)
    {

        $x = $request->X;
        $y = $request->Y;
        $picture = $request->picture;
        $id = $request->id;
        $name = $request->name;
        $href = $request->href;
        $auth = $request->auth;

        $update = $request->update;
        $allChatSessions = $request->session()->get('chatSession');

        if (!$allChatSessions)
            $allChatSessions = array();

        $chatloc = [
            'X' => $x,
            'Y' => $y,
            'id' => $id,
            'picture' => $picture,
            'name' => $name,
            'href' => $href,
            'auth' => $auth,

        ];


        if ($update == 0) {


            $request->session()->push('chatSession', $chatloc);

            return $request->session()->get('chatSession');
        } else {
            $i = 0;

            foreach ($allChatSessions as $allChatSession) {

                if ($allChatSession["id"] == $id) {
                    if ($update == -1) {

                        unset($allChatSessions[$i]);
                        $request->session()->forget('chatSession');

                        foreach ($allChatSessions as $allChatSession2) {
                            $request->session()->push('chatSession', $allChatSession2);
                        }

                    } else {
                        unset($allChatSessions[$i]);

                        $request->session()->forget('chatSession');

                        $request->session()->push('chatSession', $chatloc);
                        foreach ($allChatSessions as $allChatSession2) {
                            $request->session()->push('chatSession', $allChatSession2);
                        }


                    }

                    break;


                }
                $i++;
            }

            return $request->session()->get('chatSession');
        }


    }

    public function chatSessions(Request $request)
    {

        return $request->session()->get('chatSession');


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
            $text = substr($text, 0, $len);
            $text = preg_replace('/\*\*(.*?)\*\*/', "<img src='https://cdnjs.cloudflare.com/ajax/libs/emojione/2.1.4/assets/png/$1' class='emojioneemoji pp-20'> ", $text);

            return $text . "...";
        } else

            $text = preg_replace('/\*\*(.*?)\*\*/', "<img src='https://cdnjs.cloudflare.com/ajax/libs/emojione/2.1.4/assets/png/$1' class='emojioneemoji pp-20'> ", $text);

        return $text;

    }


}
