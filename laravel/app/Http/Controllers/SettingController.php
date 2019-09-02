<?php

namespace App\Http\Controllers;


use App\Block;
use App\Branch;
use App\College_name;
use App\Conversation;
use App\Optional;
use App\Privacy;
use App\Sms;
use Carbon\Carbon;
use Cloudder;
use Illuminate\Http\Request;
use Sentinel;


class SettingController extends Controller
{

    public function index(Request $request, $pageid = null)
    {

        $ajax = false;
        if ($request->ajax()) {

            $ajax = true;
        }

        $user = Sentinel::check();

        if ($request->ajax()) {

            if ($pageid == 'account') {

                $optionals = Optional::where('user_id', $user->id)->get();
                $email = '';
                $facebook = '';
                $whatsapp = '';

                foreach ($optionals as $optional) {

                    if ($optional->type == 'email')
                        $email = $optional->data;
                    else if ($optional->type == 'facebook')
                        $facebook = $optional->data;
                    else if ($optional->type == 'whatsapp')
                        $whatsapp = $optional->data;
                }

                return view('settings.account')
                    ->with('email', $email)
                    ->with('facebook', $facebook)
                    ->with('ajax', $ajax)
                    ->with('whatsapp', $whatsapp);

            }
            elseif ($pageid == 'info') {

                $location = $user->hometown;
                $relationship = $user->relationship_id;
                $intro = $user->intro;

                return view('settings.info')
                    ->with('intro', $intro)
                    ->with('location', $location)
                    ->with('ajax', $ajax)
                    ->with('relationship', $relationship);

            }
            elseif ($pageid == 'phone') {
                $phone = $user->phone;

                return view('settings.phone')
                    ->with('ajax', $ajax)
                    ->with('phone', $phone);

            }
            elseif ($pageid == 'password') {

                return view('settings.password')
                    ->with('ajax', $ajax);

            }
            elseif ($pageid == 'privacy') {

                $privacy = Privacy::where('user_id', $user->id)->first();

                return view('settings.privacy')
                    ->with('ajax', $ajax)
                    ->with('privacy', $privacy);

            }
            elseif ($pageid == 'notification') {

                $notification = Sms::where('user_id', $user->id)->first();

                return view('settings.notification')
                    ->with('notification', $notification);

            }
            elseif ($pageid == 'blocked') {

                $blocks = Block::where('user_id1', $user->id)->latest()->get();

                $blocksChat = Conversation::where([
                    'user_id1' => $user->id,
                    'status_1' => -1,
                ])->orwhere([
                    'user_id2' => $user->id,
                    'status_2' => -1,
                ])->latest()->get();


                $blocksChats = array();

                $blocksProfile = array();

                foreach ($blocksChat as $blockChat) {

                    $otherUser = '';

                    $userDetail = array();
                    if ($blockChat->user_id1 == $user->id) {
                        $otherUser = $blockChat->user_id2;

                    } elseif ($blockChat->user_id2 == $user->id) {
                        $otherUser = $blockChat->user_id1;
                    }

                    $blockedUser = Sentinel::findUserByid($otherUser);

                    $yearCurr = Carbon::now()->year - "$blockedUser->college_year";
                    $collegeName = College_name::where('id', $blockedUser->college_name_id)->select('name')->first()->name;
                    $branchName = Branch::where('id', $blockedUser->branch_id)->select('name')->first()->name;

                    $userDetail["college"] = $collegeName;
                    $userDetail["branch"] = $branchName;
                    $userDetail["year"] = "0" . $yearCurr;
                    $userDetail["name"] = $blockedUser->name;
                    $userDetail["picture"] = $blockedUser->profile_picture_small;
                    $userDetail["username"] = $blockedUser->username;
                    $userDetail["id"] = $blockChat->id;

                    array_push($blocksChats, $userDetail);

                }

                foreach ($blocks as $block) {

                    $userDetail = array();

                    $blockedUser = Sentinel::findUserByid($block->user_id2);

                    $yearCurr = Carbon::now()->year - "$blockedUser->college_year";
                    $collegeName = College_name::where('id', $blockedUser->college_name_id)->select('name')->first()->name;
                    $branchName = Branch::where('id', $blockedUser->branch_id)->select('name')->first()->name;

                    $userDetail["college"] = $collegeName;
                    $userDetail["branch"] = $branchName;
                    $userDetail["year"] = "0" . $yearCurr;
                    $userDetail["name"] = $blockedUser->name;
                    $userDetail["picture"] = $blockedUser->profile_picture_small;
                    $userDetail["username"] = $blockedUser->username;
                    $userDetail["id"] = $blockedUser->id;

                    array_push($blocksProfile, $userDetail);

                }

                $blockme = Block::where('user_id2', $user->id)->count();

                return view('settings.blocked')
                    ->with('ajax', $ajax)
                    ->with('blocks', $blocksProfile)
                    ->with('blocksChat', $blocksChats)
                    ->with('blockme', $blockme);

            }
            else {
                $optionals = Optional::where('user_id', $user->id)->get();
                $email = '';
                $facebook = '';
                $whatsapp = '';

                $phone = $user->phone;
                $location = $user->hometown;
                $relationship = $user->relationship_id;
                $intro = $user->intro;

                foreach ($optionals as $optional) {

                    if ($optional->type == 'email')
                        $email = $optional->data;
                    else if ($optional->type == 'facebook')
                        $facebook = $optional->data;
                    else if ($optional->type == 'whatsapp')
                        $whatsapp = $optional->data;
                }

                $privacy = Privacy::where('user_id', $user->id)->first();
                $notification = Sms::where('user_id', $user->id)->first();

                $blocks = Block::where('user_id1', $user->id)->latest()->get();

                $blocksChat = Conversation::where([
                    'user_id1' => $user->id,
                    'status_1' => -1,
                ])->orwhere([
                    'user_id2' => $user->id,
                    'status_2' => -1,
                ])->latest()->get();

                $blocksChats = array();

                $blocksProfile = array();

                foreach ($blocksChat as $blockChat) {

                    $otherUser = '';

                    $userDetail = array();
                    if ($blockChat->user_id1 == $user->id) {
                        $otherUser = $blockChat->user_id2;

                    } elseif ($blockChat->user_id2 == $user->id) {
                        $otherUser = $blockChat->user_id1;
                    }

                    $blockedUser = Sentinel::findUserByid($otherUser);

                    $yearCurr = Carbon::now()->year - "$blockedUser->college_year";
                    $collegeName = College_name::where('id', $blockedUser->college_name_id)->select('name')->first()->name;
                    $branchName = Branch::where('id', $blockedUser->branch_id)->select('name')->first()->name;

                    $userDetail["college"] = $collegeName;
                    $userDetail["branch"] = $branchName;
                    $userDetail["year"] = "0" . $yearCurr;
                    $userDetail["name"] = $blockedUser->name;
                    $userDetail["picture"] = $blockedUser->profile_picture_small;
                    $userDetail["username"] = $blockedUser->username;
                    $userDetail["id"] = $blockChat->id;

                    array_push($blocksChats, $userDetail);

                }

                foreach ($blocks as $block) {

                    $userDetail = array();

                    $blockedUser = Sentinel::findUserByid($block->user_id2);

                    $yearCurr = Carbon::now()->year - "$blockedUser->college_year";
                    $collegeName = College_name::where('id', $blockedUser->college_name_id)->select('name')->first()->name;
                    $branchName = Branch::where('id', $blockedUser->branch_id)->select('name')->first()->name;

                    $userDetail["college"] = $collegeName;
                    $userDetail["branch"] = $branchName;
                    $userDetail["year"] = "0" . $yearCurr;
                    $userDetail["name"] = $blockedUser->name;
                    $userDetail["picture"] = $blockedUser->profile_picture_small;
                    $userDetail["username"] = $blockedUser->username;
                    $userDetail["id"] = $blockedUser->id;

                    array_push($blocksProfile, $userDetail);

                }

                $blockme = Block::where('user_id2', $user->id)->count();

                return view('settings.master')
                    ->with('pageid', $pageid)
                    ->with('email', $email)
                    ->with('facebook', $facebook)
                    ->with('whatsapp', $whatsapp)
                    ->with('intro', $intro)
                    ->with('location', $location)
                    ->with('relationship', $relationship)
                    ->with('privacy', $privacy)
                    ->with('phone', $phone)
                    ->with('blocksChat', $blocksChats)
                    ->with('blocks', $blocksProfile)
                    ->with('blockme', $blockme)
                    ->with('ajax', $ajax)
                    ->with('notification', $notification);
            }

        } else {

            $optionals = Optional::where('user_id', $user->id)->get();
            $email = '';
            $facebook = '';
            $whatsapp = '';

            $phone = $user->phone;
            $location = $user->hometown;
            $relationship = $user->relationship_id;
            $intro = $user->intro;

            foreach ($optionals as $optional) {

                if ($optional->type == 'email')
                    $email = $optional->data;
                else if ($optional->type == 'facebook')
                    $facebook = $optional->data;
                else if ($optional->type == 'whatsapp')
                    $whatsapp = $optional->data;
            }

            $privacy = Privacy::where('user_id', $user->id)->first();
            $notification = Sms::where('user_id', $user->id)->first();
            $blocks = Block::where('user_id1', $user->id)->latest()->get();

            $blocksChat = Conversation::where([
                'user_id1' => $user->id,
                'status_1' => -1,
            ])->orwhere([
                'user_id2' => $user->id,
                'status_2' => -1,
            ])->latest()->get();

            $blocksChats = array();

            $blocksProfile = array();

            foreach ($blocksChat as $blockChat) {

                $otherUser = '';

                $userDetail = array();
                if ($blockChat->user_id1 == $user->id) {
                    $otherUser = $blockChat->user_id2;

                } elseif ($blockChat->user_id2 == $user->id) {
                    $otherUser = $blockChat->user_id1;
                }

                $blockedUser = Sentinel::findUserByid($otherUser);

                $yearCurr = Carbon::now()->year - "$blockedUser->college_year";
                $collegeName = College_name::where('id', $blockedUser->college_name_id)->select('name')->first()->name;
                $branchName = Branch::where('id', $blockedUser->branch_id)->select('name')->first()->name;

                $userDetail["college"] = $collegeName;
                $userDetail["branch"] = $branchName;
                $userDetail["year"] = "0" . $yearCurr;
                $userDetail["name"] = $blockedUser->name;
                $userDetail["picture"] = $blockedUser->profile_picture_small;
                $userDetail["username"] = $blockedUser->username;
                $userDetail["id"] = $blockChat->id;

                array_push($blocksChats, $userDetail);

            }

            foreach ($blocks as $block) {

                $userDetail = array();

                $blockedUser = Sentinel::findUserByid($block->user_id2);

                $yearCurr = Carbon::now()->year - "$blockedUser->college_year";
                $collegeName = College_name::where('id', $blockedUser->college_name_id)->select('name')->first()->name;
                $branchName = Branch::where('id', $blockedUser->branch_id)->select('name')->first()->name;

                $userDetail["college"] = $collegeName;
                $userDetail["branch"] = $branchName;
                $userDetail["year"] = "0" . $yearCurr;
                $userDetail["name"] = $blockedUser->name;
                $userDetail["picture"] = $blockedUser->profile_picture_small;
                $userDetail["username"] = $blockedUser->username;
                $userDetail["id"] = $blockedUser->id;

                array_push($blocksProfile, $userDetail);

            }

            $blockme = Block::where('user_id2', $user->id)->count();

            return view('settings.master')
                ->with('pageid', $pageid)
                ->with('email', $email)
                ->with('facebook', $facebook)
                ->with('whatsapp', $whatsapp)
                ->with('intro', $intro)
                ->with('location', $location)
                ->with('relationship', $relationship)
                ->with('privacy', $privacy)
                ->with('phone', $phone)
                ->with('blocks', $blocksProfile)
                ->with('blocksChat', $blocksChats)
                ->with('blockme', $blockme)
                ->with('ajax', $ajax)
                ->with('notification', $notification);

        }
    }

    public function update(Request $request, $pageid = null)
    {
        $user = Sentinel::check();



        if ($pageid == 'account') {

            if($user->username == $request->username){
                $this->validate($request, [
                    'email' => 'bail|email',
                    'facebook' => 'bail|url',
                    'whatsapp' => 'bail|integer|digits:10',
                ]);

            }
            else {
                $this->validate($request, [
                    'username' => 'bail|required|alpha_dash|max:20|unique:users,username',
                    'email' => 'bail|email',
                    'facebook' => 'bail|url',
                    'whatsapp' => 'bail|integer|digits:10',
                ]);

                $credentials = [
                    'username' => $request->username,
                ];
                Sentinel::update($user, $credentials);

            }



            Optional::where([
                'type' => 'email',
                'user_id' => $user->id,
            ])->update([
                'data' => $request->email
            ]);

            Optional::where([
                'type' => 'facebook',
                'user_id' => $user->id,
            ])->update([
                'data' => $request->facebook
            ]);

            Optional::where([
                'type' => 'whatsapp',
                'user_id' => $user->id,
            ])->update([
                'data' => $request->whatsapp
            ]);

        }
        elseif ($pageid == 'info') {

            $credentials = [
                'intro' => $request->intro,
                'hometown' => $request->mylocation,
                'relationship_id' => $request->relationship
            ];
            Sentinel::update($user, $credentials);


        }
        elseif ($pageid == 'phone') {

            $this->validate($request, [
                'phone' => 'bail|required|integer|digits:10|unique:users,phone',
            ]);


            $user = Sentinel::check();

            $phone = $request->phone;

            $data = $user->otp()->where([
                ['phone', $phone],
                ['status', 0],
            ])->orderBy('id', 'desc')->get();


            if ($totalsms = count($data)) {

                $carbon = Carbon::now()->diffInSeconds(Carbon::parse($data[0]->created_at));

                if ($totalsms >= 5) {

                    return $this->ajaxError('Maximum Limit for this phone has been reached!');

                } else if ($carbon < 30) {

                    if ($request->ajax())
                        return 'true';
                    else
                        return redirect('phone');

                    //TODO open otp box with response

                } else {
                    return $this->SendSms($user, $request);
                }

            } else {
                return $this->SendSms($user, $request);
            }


        }
        elseif ($pageid == 'password') {


            $hasher = Sentinel::getHasher();

            if (!$hasher->check($request->oldPassword, $user->password) || $request->password != $request->passwordConf) {
                return $this->ajaxError('Current password not matched!!');

            } else {
                Sentinel::update($user, array('password' => $request->password));
            }
        }
        elseif ($pageid == 'privacy') {
            $name = '';
            $data = '';

            if ($request->followers) {
                $name = 'followers';
                $data = $request->followers;

            } elseif ($request->following) {
                $name = 'following';
                $data = $request->following;

            } elseif ($request->facematch) {
                $name = 'facematch';
                $data = $request->facematch;

            } elseif ($request->phone) {
                $name = 'phone';
                $data = $request->phone;

            } elseif ($request->message) {
                $name = 'message';
                $data = $request->message;

            } elseif ($request->tags) {
                $name = 'tags';
                $data = $request->tags;

            } elseif ($request->anonymous) {
                $name = 'anonymous';
                $data = $request->anonymous;
            }

            if ($data == 'false')
                $data = 0;
            elseif ($data == 'true')
                $data = 1;

            Privacy::where([
                'user_id' => $user->id,
            ])->update([$name => $data]);

        }
        elseif ($pageid == 'notification') {

            $name = '';
            $data = '';

            if ($request->login) {
                $name = 'login';
                $data = $request->login;

            } elseif ($request->messages) {
                $name = 'messages';
                $data = $request->messages;

            } elseif ($request->notifications) {
                $name = 'notifications';
                $data = $request->notifications;

            } elseif ($request->anonymous) {
                $name = 'anonymous';
                $data = $request->anonymous;

            }

            if ($data == 'false')
                $data = 0;
            elseif ($data == 'true')
                $data = 1;

            Sms::where([
                'user_id' => $user->id,
            ])->update([$name => $data]);
        }
        elseif ($pageid == 'blocked') {

            $this->validate($request, [
                'userid' => 'bail|required|integer|exists:users,id',
            ], [
                    'userid.*' => 'Invalid Post!!',
                ]
            );

            Block::where([
                'user_id1' => $user->id,
                'user_id2' => $request->userid,
            ])->delete();
        }

    }

    public function SendSms($user, $request)
    {
        $randomOtp = rand (1000 ,9999);
        //   $randomOtp = 1000 ;
        $phoneNumber = $request->phone;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://2factor.in/API/V1/17297c8b-f9e1-11e6-9462-00163ef91450/SMS/$phoneNumber/$randomOtp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
        ));

        curl_exec($curl);
        curl_error($curl);

        curl_close($curl);


        $user->otp()->create([
            'phone' => $request->phone,
            'otp' => $randomOtp,
        ]);
        if ($request->ajax())
            return 'true';
        else
            return redirect('phone');

    }

    public function phoneupdateotp(Request $request)
    {
        $this->validate($request, [
            'otp' => 'bail|required|integer|digits:4',
        ]);

        $user = Sentinel::check();

        $data = $user->otp()->orderBy('id', 'desc')->first();

        if (count($data)) {
            $userotp = $request->otp;
            $sysotp = $data->otp;
            $status = $data->status;
            $carbon = Carbon::now()->diffInSeconds(Carbon::parse($data->created_at));
            $phone = $data->phone;
            if ($userotp == $sysotp && $status == 0) {

                if ($carbon <= 60) {
                    $credentials = [
                        'phone' => $phone,
                    ];

                    $user->otp()->where('status', 0)
                        ->where('phone', $phone)
                        ->where('otp', $sysotp)
                        ->update(['status' => 1]);

                    Sentinel::update($user, $credentials);

                    if ($request->ajax())
                        return 'true';
                    else
                        return redirect('info');
                } else {

                    return $this->ajaxError('OTP is Expired !!');
                }
            } else {
                return $this->ajaxError('Invalid OTP !!');
            }

        } else {
            return $this->ajaxError('Invalid OTP!!');

        }

    }

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

    }

}
