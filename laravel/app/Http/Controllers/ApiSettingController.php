<?php

namespace App\Http\Controllers;

use App\Otp;
use App\Reports;
use Illuminate\Http\Request;
use App\oAuth;
use App\Optional;
use App\Privacy;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use App\UnfollowPost;
use DB;

class ApiSettingController extends Controller
{
    public function changePassword(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $validator = Validator::make($request->all(), [
                'Current_password' => 'bail|required|max:20|min:6',
            ]);

            if ($validator->fails())
                return $validator->messages()->get("Current_password")[0];


            $validator = Validator::make($request->all(), [
                'new_password' => 'bail|required|max:20|min:6',
            ]);

            if ($validator->fails())
                return $validator->messages()->get("new_password")[0];


            $validator = Validator::make($request->all(), [
                'confirm_password' => 'bail|required|max:20|min:6|same:password',

            ]);

            if ($validator->fails())
                return $validator->messages()->get("confirm_password")[0];


            $userID = $user->user_id;
            $prev = $request->Current_password;
            $new = $request->new_password;
            $confirm = $request->confirm_password;

            $user = Sentinel::findById($userID);

            Sentinel::authenticate(array(
                'username' => $user->username,
                'password' => $prev,
            ), false);

            if ($user = Sentinel::check()) {

                Sentinel::update($user, array('password' => $new));
                return "1";
            }

        }
    }


    public function changePhoneno(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $post_id = $request->post_id;

            UnfollowPost::create([
                'post_id' => $post_id,
                'user_id' => $userID,
            ]);

        }
    }


    public function contactUS(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $msg = $request->message;

            Reports::create([
                'source' => $userID,
                'type' => 'fabits',
                'comment' => $msg
            ]);

        }
    }

    public function logout(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            oAuth::where([
                'user_id' => $userID,
            ])->delete();
        }
    }


    public function otp(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $phone = $request->phone;

            $data = Otp::where([
                ['phone', $phone],
                ['status', 0],
                ['user_id', $userID],
            ])->orderBy('id', 'desc')->get();


            if ($totalsms = count($data)) {

                $carbon = Carbon::now()->diffInSeconds(Carbon::parse($data[0]->created_at));

                if ($totalsms >= 5) {

                    return 'Maximum Limit for this phone has been reached!';

                } else if ($carbon < 30) {

                    return 'true';


                    //TODO open otp box with response

                } else {
                    return $this->SendSms($userID, $request);
                }

            } else {
                return $this->SendSms($userID, $request);
            }

        }
    }


    public function SendSms($userID, $request)
    {
        $randomOtp = rand(1000, 9999);
//        $randomOtp = 1001;
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


        Otp::create([
            'phone' => $request->phone,
            'otp' => $randomOtp,
            'user_id' => $userID
        ]);
        return 'true';


    }

    private function setPhone($userID, $value, $value1)
    {

        $data = Otp::where('user_id', $userID)->orderBy('id', 'desc')->first();

        if (count($data)) {
            $userotp = $value;
            $sysotp = $data->otp;
            $status = $data->status;
            $carbon = Carbon::now()->diffInSeconds(Carbon::parse($data->created_at));
            $phone = $data->phone;
            if ($userotp == $sysotp && $status == 0) {

                if ($carbon <= 60) {

                    Otp::where('status', 0)
                        ->where('phone', $phone)
                        ->where('user_id', $userID)
                        ->where('otp', $sysotp)
                        ->update(['status' => 1]);

                    if ($value1 == 's')
                        $cradential = [
                            'phone' => $phone,
                            'status' => '2'
                        ];
                    else

                        $cradential = [
                            'phone' => $phone,
                        ];

                    DB::table('users')
                        ->where('id', $userID)
                        ->update($cradential);


                    return "1";

                } else {

                    return 'OTP is Expired !!';
                }
            } else {
                return 'Invalid OTP !!';
            }

        } else {
            return 'Invalid OTP!!';

        }
    }


    public function settings(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;
            $setting_name = $request->setting_name;

            switch ($setting_name) {

                case 'S_STATUS':
                    $validator = Validator::make($request->all(), [
                        'S_STATUS' => 'bail|required'
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_STATUS")[0];

                    $value = $request->S_STATUS;
                    return $this->setSetting($userID, 'intro', $value);
                    break;

                case 'S_USERNAME':
                    $validator = Validator::make($request->all(), [
                        'S_USERNAME' => 'bail|required|alpha_dash|max:20|unique:users,username',
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_USERNAME")[0];

                    $value = $request->S_USERNAME;
                    return $this->setSetting($userID, 'username', $value);
                    break;

                case 'S_EMAIL':
                    $validator = Validator::make($request->all(), [
                        'S_EMAIL' => 'bail|email',
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_EMAIL")[0];

                    $value = $request->S_EMAIL;
                    $this->setOptionalSetting($userID, 'email', $value);
                    break;

                case 'S_FACEBOOK':
                    $validator = Validator::make($request->all(), [
                        'S_FACEBOOK' => 'bail|url',
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_FACEBOOK")[0];

                    $value = $request->S_FACEBOOK;
                    $this->setOptionalSetting($userID, 'facebook', $value);
                    break;

                case 'S_WHATSAPP':

                    $validator = Validator::make($request->all(), [
                        'S_WHATSAPP' => 'bail|integer|digits:10',
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_WHATSAPP")[0];

                    $value = $request->S_WHATSAPP;
                    $this->setOptionalSetting($userID, 'whatsapp', $value);
                    break;

                case 'S_OTP':
                    $validator = Validator::make($request->all(), [
                        'S_OTP' => 'bail|integer|digits:4',
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_OTP")[0];


                    $value = $request->S_OTP;
                    $value1 = $request->status;
                    return $this->setPhone($userID, $value, $value1);
                    break;

                case 'S_LOCATION':
                    $validator = Validator::make($request->all(), [
                        'S_LOCATION' => 'bail|required',
                    ]);

                    if ($validator->fails())
                        return $validator->messages()->get("S_LOCATION")[0];

                    $value = $request->S_LOCATION;
                    return $this->setSetting($userID, 'hometown', $value);
                    break;

                case 'S_RELATIONSHIP':

                    $value = $request->S_RELATIONSHIP;
                    if ($value == 'Single')
                        $value = 1;
                    else if ($value == 'Committed')
                        $value = 2;
                    else if ($value == 'Complicated')
                        $value = 3;
                    else
                        return "Invalid";

                    return $this->setSetting($userID, 'relationship_id', $value);
                    break;

                case 'S_P_PHONE':
                    $value = $request->S_P_PHONE == "Private" ? 0 : 1;

                    return $this->setPrivacySetting($userID, 'phone', $value);
                    break;
                case 'S_P_FOLLOWERS':
                    $value = $request->S_P_FOLLOWERS == "Private" ? 0 : 1;
                    return $this->setPrivacySetting($userID, 'followers', $value);
                    break;
                case 'S_P_FOLLOWING':
                    $value = $request->S_P_FOLLOWING == "Private" ? 0 : 1;
                    return $this->setPrivacySetting($userID, 'following', $value);
                    break;
                case 'S_P_FACEMATCH':
                    $value = $request->S_P_FACEMATCH == "Private" ? 0 : 1;
                    return $this->setPrivacySetting($userID, 'facematch', $value);
                    break;
                case 'S_A_LOGIN':
                    $value = $request->S_A_LOGIN == "Yes" ? 0 : 1;
                    return $this->setSmsSetting($userID, 'login', $value);
                    break;
                case 'S_A_MESSAGE':
                    $value = $request->S_A_MESSAGE == "Yes" ? 0 : 1;
                    return $this->setSmsSetting($userID, 'messages', $value);
                    break;
                case 'S_A_NOTIFICATION':
                    $value = $request->S_A_NOTIFICATION == "Yes" ? 0 : 1;
                    return $this->setSmsSetting($userID, 'notifications', $value);
                    break;
                case 'S_A_ANONY_MESSAGE':
                    $value = $request->S_A_ANONY_MESSAGE == "Yes" ? 0 : 1;
                    return $this->setSmsSetting($userID, 'anonymous', $value);
                    break;


            }


        }

    }

    public function setSetting($UserID, $column, $value)
    {

        DB::table('users')
            ->where('id', $UserID)
            ->update([
                $column => $value,
            ]);

        return "1";

    }

    public function setOptionalSetting($UserID, $column, $value)
    {

        Optional::where([
            'type' => $column,
            'user_id' => $UserID,
        ])->update([
            'data' => $value
        ]);

    }

    public function setSmsSetting($UserID, $column, $value)
    {

        Sms::where([
            'user_id' => $UserID,
        ])->update([$column => $value]);

        return "1";
    }

    public function setPrivacySetting($UserID, $column, $value)
    {

        Privacy::where([
            'user_id' => $UserID,
        ])->update([$column => $value]);

        return "1";

    }


}
