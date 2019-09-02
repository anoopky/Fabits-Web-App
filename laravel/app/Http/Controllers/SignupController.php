<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Cloudder;
use Exception;
use Illuminate\Http\Request;
use Sentinel;


class SignupController extends Controller
{

    public function password()
    {
        return view('signup.password_change');
    }

    public function passwordupdate(Request $request)
    {
        $this->validate($request, [
            'password' => 'bail|required|max:20|min:6',
            'confirm_password' => 'bail|required|max:20|min:6|same:password',
        ]);

        $password = $request->password;

        $user = Sentinel::check();
        $credentials = [
            'password' => $password,
            'status' => '1',
        ];

        Sentinel::update($user, $credentials);

        if ($request->ajax())
            return 'true';
        else
            return redirect('phone');

    }

    public function passwordskip(Request $request)
    {
        $user = Sentinel::check();
        $credentials = [
            'status' => '1',
        ];
        Sentinel::update($user, $credentials);
        return redirect('phone');
    }

    public function phone()
    {
        return view('signup.phone');
    }

    public function phoneupdate(Request $request)
    {
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

            if ($totalsms >= 10) {

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
//            return $response;
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
            if ($userotp == $sysotp && $status == 0 ) {

                if($carbon <= 100) {
                    $credentials = [
                        'phone' => $phone,
                        'status' => '2',
                    ];

//                    Sentinel::update($user, $credentials);

                    $user->otp()->where('status', 0)
                        ->where('phone', $phone)
                        ->where('otp', $sysotp)
                        ->update(['status' => 1]);

                    Sentinel::update($user, $credentials);

                    if ($request->ajax())
                        return 'true';
                    else
                        return redirect('info');
                }else{

                    return $this->ajaxError('OTP is Expired !!');
                }
            } else {
                return $this->ajaxError('Invalid OTP !!');
            }

        } else {
            return $this->ajaxError('Invalid OTP!!');

        }

    }

    public function info()
    {
        return view('signup.info');
    }

    public function infoupdate(Request $request)
    {

        $this->validate($request, [
            'gender' => 'bail|required|integer|between:0,1',
            'birthday_day' => 'bail|required|integer|between:1,31',
            'birthday_month' => 'bail|required|integer|between:1,12',
            'birthday_year' => 'bail|required|integer|between:1971,2016',

        ], [

            'gender.between' => 'The gender must be either Male or Female',
        ]);

        $user = Sentinel::check();

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

        Sentinel::update($user, $credentials);
        if ($request->ajax())
            return 'true';
        else
            return redirect('profile');
    }

    public function profile()
    {
        return view('signup.profile_pic');
    }

    public function profileupload(Request $request)
    {
        $this->validate($request, [
            'profile_picture' => 'required | image',
        ]);

        try {

            Cloudder::upload($request->file('profile_picture'), null,
                array(
                    "format" => "jpg", "crop" => "crop", "x" => 0, "y" => 0,
                    "width" => 200, "height" => 200, "crop" => "thumb",
                    "gravity" => "face",
                ));
        } catch (Exception $e) {
            return $this->ajaxError('Invalid Image format !');
        }

        $image = Cloudder::getResult();
        $image = $image["public_id"];

        $request->session()->push('profile_images', $image);
        if ($request->ajax())
            return Cloudder::show($image, array());
        else
            return redirect('profile');

    }

    public function profileskip(Request $request)
    {
        if ($request->session()->has('profile_images')) {

            $images = $request->session()->get('profile_images');
            foreach ($images as $key => $value) {
                Cloudder::destroyImage($value, array());
            }
            $request->session()->forget('profile_images');

        }
        $user = Sentinel::check();
        $credentials = [
            'status' => '4',
        ];
        Sentinel::update($user, $credentials);

        if ($request->ajax())
            return 'true';
        else
            return redirect('home');

    }

    public function profilesave(Request $request)
    {
        if (!$request->session()->has('profile_images'))
            return $this->ajaxError('Please select a image or skip !');
        else {
            $images = $request->session()->get('profile_images');
            $numItems = count($images);
            $i = 0;
            foreach ($images as $key => $value) {

                if (++$i === $numItems) {

                    Cloudder::upload(Cloudder::show($value, array()), null,
                        array(
                            "width" => 75, "height" => 75,
                        ));
                    $image = Cloudder::getResult();
                    $image = $image["public_id"];
                    $user = Sentinel::check();
                    $credentials = [
                        'profile_picture_big' => $value,
                        'profile_picture_small' => $image,
                        'status' => '4',
                    ];
                    $user = Sentinel::update($user, $credentials);

                } else {
                    Cloudder::destroyImage($value, array());
                }
                $request->session()->pull('profile_images', $value);
            }

            if ($request->ajax())
                return 'true';
            else
                return redirect('home');
        }

    }

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

    }


}
