<?php

namespace App\Http\Controllers;

use App\Otp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentinel;

class ResetPassword extends Controller
{

    public function resetInit(Request $request)
    {

        $this->validate($request, [
            'username' => 'bail|required|alpha_dash|max:20|exists:users,username',
            'phone' => 'bail|required|integer|digits:10|exists:users,phone',
        ],
            [
                'username.*' => 'Invalid request !!',
                'phone.*' => 'Invalid request !!',

            ]);

        $result = DB::table('users')
            ->where('username', '=', $request->username)
            ->where('phone', '=', $request->phone)
            ->first();

        if (count($result) > 0) {

            $phone = $request->phone;

            $data = Otp::where([
                ['user_id', $result->id],
                ['phone', $phone],
                ['status', 100],
            ])->orderBy('id', 'desc')->get();


            if ($totalsms = count($data)) {

                $carbon = Carbon::now()->diffInSeconds(Carbon::parse($data[0]->created_at));

                if ($totalsms >= 5) {

                    return $this->ajaxError('Maximum Limit for this phone has been reached!');

                } else if ($carbon < 0) {

                    if ($request->ajax())
                        return 'true';

                } else {

                    $request->session()->put('ResetPassword-1', $result->id);
                    return $this->SendSms($result, $request);
                }

            } else {

                $request->session()->put('ResetPassword-1', $result->id);
                return $this->SendSms($result, $request);
            }


        }
        else{
            return $this->ajaxError('Invalid Request !!');

        }

    }

    public function resetOTP(Request $request)
    {


        $this->validate($request, [
            'otp' => 'bail|required|integer|digits:4',
        ]);

        if ($request->session()->has('ResetPassword-1')) {

            $user = $request->session()->get('ResetPassword-1');

//return $user;
            $data =  Otp::where('user_id', $user)
                ->orderBy('id', 'desc')->first();

            if (count($data)) {
                $userotp = $request->otp;
                $sysotp = $data->otp;
                $status = $data->status;
                $carbon = Carbon::now()->diffInSeconds(Carbon::parse($data->created_at));
                $phone = $data->phone;
                if ($userotp == $sysotp && $status == 100) {

                    if ($carbon <= 60) {

                        Otp::where('status', 100)
                            ->where('phone', $phone)
                            ->where('user_id', $user)
                            ->where('otp', $sysotp)
                            ->update(['status' => 101]);

                        $request->session()->put('ResetPassword-2', $user);

                        if ($request->ajax())
                            return 'true';

                    } else {

                        return $this->ajaxError('OTP is Expired !!');
                    }
                } else {
                    return $this->ajaxError('Invalid OTP !!');
                }

            } else {
                return $this->ajaxError('Invalid OTP!!');

            }


        } else {
            return $this->ajaxError('Invalid Request!!');
        }
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'bail|required|max:20|min:6',
            'confirm_password' => 'bail|required|max:20|min:6|same:password',
        ]);

        if ($request->session()->has('ResetPassword-2')) {

            $userID = $request->session()->get('ResetPassword-2');

            $user = Sentinel::findById($userID);

            $password = $request->password;

            $credentials = [
                'password' => $password,
            ];

            Sentinel::update($user, $credentials);

            Sentinel::authenticate(array(
                'username' => $user->username,
                'password' => $password,
            ), true);

            if ($request->ajax())
                return 'true';
            else
                return redirect('home');

        } else {
            return $this->ajaxError('Invalid Request!!');
        }

    }

    public function SendSms($user, $request)
    {
        $randomOtp = rand(1000, 9999);
//        $randomOtp = 1000;

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
            'user_id' => $user->id,
            'phone' => $request->phone,
            'otp' => $randomOtp,
            'status' => 100,
        ]);
        if ($request->ajax())
            return 'true';
        else
            return redirect('phone');

    }

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

    }


}
