<?php

namespace App\Http\Controllers;

use Cloudder;
use Carbon\Carbon;
use DB;
use Sentinel;
use App\oAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiSignUpController extends Controller
{
    public function signUpPassword(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $validator = Validator::make($request->all(), [
                'password' => 'bail|required|max:20|min:6',
            ]);

            if ($validator->fails())
                return $validator->messages()->get("password")[0];

            $validator = Validator::make($request->all(), [
                'confirm_password' => 'bail|required|max:20|min:6|same:password',

            ]);

            if ($validator->fails())
                return $validator->messages()->get("confirm_password")[0];

            $userID = $user->user_id;
            $new = $request->password;

            $user = Sentinel::findById($userID);


            Sentinel::update($user,
                array('password' => $new,
                    'status' => '1',)
            );

            return "1";

        }
    }


    public function signUpPasswordSkip(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $userID = $user->user_id;

            DB::table('users')
                ->where('id', $userID)
                ->update([
                    'status' => '2',
                ]);
            return "1";

        }
    }

    public function signUpGenderDob(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $userID = $user->user_id;
            $gender = $request->gender;
            $birthday_day = $request->birthday_day;
            $birthday_month = $request->birthday_month;
            $birthday_year = $request->birthday_year;

            $credentials = [
                'gender' => $gender,
                'birth_day' => $birthday_day,
                'birth_month' => $birthday_month,
                'birth_year' => $birthday_year,
                'status' => '3',
            ];

            DB::table('users')
                ->where('id', $userID)
                ->update($credentials);


            return "1";

        }
    }

    public function signUpProfilePicSkip(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $userID = $user->user_id;
            DB::table('users')
                ->where('id', $userID)
                ->update([
                    'status' => '4',
                ]);

            return '1';
        }
    }

    public function signUpProfilePic(Request $request, $token)
    {
        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $userID = $user->user_id;

            try {

                Cloudder::upload($request->file('uploadfile'), null,
                    array(
                        "format" => "jpg", "crop" => "crop", "x" => 0, "y" => 0,
                        "width" => 500, "height" => 500, "crop" => "thumb",
                    ));
            } catch (\Exception $e) {
                return 'Invalid Image format !';
            }

            $image2 = Cloudder::getResult();
            $image = $image2["public_id"];

            try {

                Cloudder::upload(Cloudder::show($image, array()), null,
                    array(
                        "width" => 75, "height" => 75,
                    ));

            } catch (\Exception $e) {
                return 'Invalid Image format !';
            }

            $image007 = Cloudder::getResult();
            $image008 = $image007["public_id"];

            DB::table('users')
                ->where('id', $userID)
                ->update([
                    'profile_picture_big' => $image,
                    'profile_picture_small' => $image008,
                ]);

            return Cloudder::show($image, array());

        }
    }

}
