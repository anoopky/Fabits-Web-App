<?php

namespace App\Http\Controllers;

use App\Optional;
use App\Privacy;
use App\Sms;
use Carbon\Carbon;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cloudder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sentinel;


class LoginController extends Controller
{
    public function index()
    {
        $formatted_date = Carbon::now()->subMinutes(5)->toDateTimeString();

        $result = DB::table('users')
            ->orderby('last_seen', 'desc')
            ->offset(0)
            ->limit(6)
//            ->where('last_seen', '>=', $formatted_date)
            ->get();

        $online_data = array();

        foreach ($result as $key => $value) {

            $online = array();
            $online["user_name"] = $value->name;
            $online["id"] = $value->id;
            $online["user_picture"] = Cloudder::show($value->profile_picture_small, array());
            array_push($online_data, $online);
        }

        return view('login.index')
            ->with('online_data', $online_data);

    }

    public function about()
    {

        return view('login.about');

    }

    public function terms()
    {

        return view('login.terms');

    }

    public function loginpolicy()
    {

        return view('login.login');

    }

    public function privacy()
    {

        return view('login.privacy');

    }

    public function createUser(Request $request)
    {

        try {

            $this->validate($request, [
                'username' => 'bail|required|alpha_dash|max:20',
                'password' => 'bail|required|max:20',
            ]);
            $username = $request->username;
            $password = $request->password;


            $remember = (bool)($request->remember == 'true' ? true : false);

            Sentinel::authenticate(array(
                'username' => $username,
                'university_id' => $username,
                'phone' => $username,
                'college_id' => $username,
                'password' => $password,
            ), $remember);

            if ($user = Sentinel::check()) {
                if ($request->ajax())
                    return 'true';
                else
                    return redirect('home');

            } else {

                Sentinel::authenticate(array(
                    'username' => $username,
                    'college_id' => $username,
                    'password' => '945)4!@%87128&^)260$(135*65',
                ));

                if ($user = Sentinel::check()) {

                    $userPSIT = isUserFromPSIT($user->college_id, $password);
                    $userPSIT_decode = json_decode($userPSIT);
                    if (count($userPSIT_decode->user_details)) {

                        $userPSIT_rollno = $userPSIT_decode->user_details[0]->RollNo;
                        $userPSIT_userid = $userPSIT_decode->user_details[0]->userid;

                        if ($userPSIT_userid == $user->college_id) {

                            $credentials = [
                                'password' => $password,
                                'last_seen' => Carbon::now(),
                                'status' => 0,
                            ];
                            Sentinel::update($user, $credentials);


                            Privacy::create([
                                'user_id' => $user->id,
                                'followers' => 1,
                                'following' => 1,
                                'facematch' => 1,
                                'phone' => 1,
                                'message' => 1,
                                'tags' => 1,
                            ]);

                            SMS::create([
                                'user_id' => $user->id,
                                'login' => 0,
                                'messages' => 0,
                                'notifications' => 0,
                                'anonymous' => 0,
                            ]);

                            $data = array(
                                array('type' => 'email', 'user_id' => $user->id, 'data' => ''),
                                array('type' => 'facebook', 'user_id' => $user->id, 'data' => ''),
                                array('type' => 'whatsapp', 'user_id' => $user->id, 'data' => ''),
                            );

                            Optional::insert($data);

                            if ($request->ajax())
                                return 'true';
                            else
                                return redirect('home');

                        } else {
                            Sentinel::logout();
                            return $this->ajaxError('Invalid Password!! ', $request);
                        }
                    } else {
                        Sentinel::logout();
                        return $this->ajaxError('Invalid Password!! ', $request);
                    }

                } else {

                    $userPSIT = isUserFromPSIT($username, $password);
                    $helpMessage = '';
                    $userPSIT_decode = json_decode($userPSIT);
                    if (strlen($username) == 5) {

                        $helpMessage = 'Try using your University Id and College password !!';

                    } elseif (strlen($username) == 10) {

                        $helpMessage = 'Try using your College Id (5 digits ID ex: 18888 ) and College password !!';

                    } elseif (strlen($username) == 6) {

                        $helpMessage = 'Try using your College Id (5 digits ID ex: 18888 ) and College password !!';
                    }


                    if (count($userPSIT_decode->user_details)) {

                        $userPSIT_name = $userPSIT_decode->user_details[0]->name;
                        $userPSIT_rollno = $userPSIT_decode->user_details[0]->RollNo;
                        $userPSIT_userid = $userPSIT_decode->user_details[0]->userid;

                        $college_id = $userPSIT_userid;

                        $college = null;
                        $branch = null;
                        $yearofjoin = null;


                        if (strlen($userPSIT_rollno) == 10) {
                            $yearofjoin = '20' . substr($userPSIT_rollno, 0, 2);
                            $college = substr($userPSIT_rollno, 2, 3);
                            $branch = substr($userPSIT_rollno, 5, 2);
                        }


                        if ($userPSIT_userid == $username) {

                            //TODO check for image is valid or not
                            Cloudder::upload("https://www.psit.in/ProjectCollege/STUDENT/images/{$college_id}.jpg", null,
                                array(
                                    "format" => "jpg", "crop" => "crop", "x" => 0, "y" => 0,
                                    "width" => 200, "height" => 200, "crop" => "thumb",
                                ));

                            $image = Cloudder::getResult();
                            $image = $image["public_id"];

                            Cloudder::upload(Cloudder::show($image, array()), null,
                                array(
                                    "width" => 75, "height" => 75,
                                ));

                            $image1 = Cloudder::getResult();
                            $image1 = $image1["public_id"];
                            try {

                                Sentinel::registerAndActivate(array(
                                    'username' => $userPSIT_rollno,
                                    'password' => $password,
                                    'name' => $userPSIT_name,
                                    'college_id' => $college_id,
                                    'university_id' => $userPSIT_rollno,
                                    'profile_picture_big' => $image,
                                    'profile_picture_small' => $image1,
                                    'college_year' => $yearofjoin,
                                    'college_name_id' => $college,
                                    'branch_id' => $branch,
                                    'last_seen' => Carbon::now(),
                                    'status' => 0,
                                    'hometown' => "-",
                                    'relationship_id' => 0,
                                ));


                            } catch (\Exception $e) {

                                return $this->ajaxError('You have changed your password !', $request);
                            }

                            $userthis = Sentinel::authenticate(array(
                                'username' => $userPSIT_rollno,
                                'password' => $password,
                            ), $remember);


                            if (Sentinel::check()) {

                                Privacy::create([
                                    'user_id' => $userthis->id,
                                    'followers' => 1,
                                    'following' => 1,
                                    'facematch' => 1,
                                    'phone' => 1,
                                    'message' => 1,
                                    'tags' => 1,
                                ]);

                                SMS::create([
                                    'user_id' => $userthis->id,
                                    'login' => 0,
                                    'messages' => 0,
                                    'notifications' => 0,
                                    'anonymous' => 0,
                                ]);

                                $data = array(
                                    array('type' => 'email', 'user_id' => $userthis->id, 'data' => ''),
                                    array('type' => 'facebook', 'user_id' => $userthis->id, 'data' => ''),
                                    array('type' => 'whatsapp', 'user_id' => $userthis->id, 'data' => ''),
                                );

                                Optional::insert($data);

                                if ($request->ajax())
                                    return 'true';
                                else
                                    return redirect('home');
                            } else {

                                return $this->ajaxError('Server Error !! ', $request);
                            }

                        } else {

                            return $this->ajaxError('Invalid Password!! ', $request);

                        }

                    } else {

                        return $this->ajaxError('Can\'t find you in fabits.in or in psit.in !! <br> ' . $helpMessage, $request);
                    }
                }
            }

        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return $this->ajaxError("You are banned for $delay seconds.", $request);
        }
    }


    public function init(Request $request)
    {


        Sentinel::registerAndActivate(array(
            'username' => 'psit',
            'password' => 'psit12345',
            'name' => 'PSIT Kanpur',
            'college_id' => 0,
            'university_id' => 0,
            'profile_picture_big' => 'ec2ef755-ddc8-4c47-ab10-3f77351bc532_hl5mc3',
            'profile_picture_small' => 'ec2ef755-ddc8-4c47-ab10-3f77351bc532_hl5mc3',
            'college_year' => 2017,
            'college_name_id' => 101010,
            'branch_id' => 101010,
            'last_seen' => Carbon::now(),
            'status' => 0,
            'hometown' => "-",
            'relationship_id' => 0,
        ));

        $userthis = Sentinel::authenticate(array(
            'username' => 'psit',
            'password' => 'psit12345',
        ), true);


        if (Sentinel::check()) {

            Privacy::create([
                'user_id' => $userthis->id,
                'followers' => 1,
                'following' => 1,
                'facematch' => 1,
                'phone' => 1,
                'message' => 1,
                'tags' => 1,
            ]);

            SMS::create([
                'user_id' => $userthis->id,
                'login' => 0,
                'messages' => 0,
                'notifications' => 0,
                'anonymous' => 0,
            ]);

            $data = array(
                array('type' => 'email', 'user_id' => $userthis->id, 'data' => ''),
                array('type' => 'facebook', 'user_id' => $userthis->id, 'data' => ''),
                array('type' => 'whatsapp', 'user_id' => $userthis->id, 'data' => ''),
            );

            Optional::insert($data);


        }
    }

    public function logout(Request $request)
    {
        Sentinel::logout();
        if ($request->ajax())
            return 'true';
        else
            return redirect('/');
    }

    public function ajaxError($Message, $request)
    {
        if ($request->ajax())


            return response()->json([
                'error' => [$Message]
            ], 422);

        else
            return redirect('/');

    }

}

// -----------------get data from PSIT-------------------------------//

function isUserFromPSIT($id, $password)
{
    $postdata = http_build_query(
        array(
            'id' => $id,
            'password' => $password,
        ));
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata,
        ));
    $context = stream_context_create($opts);
    $result = file_get_contents('http://psit.in/psit/android/login_test.php', false, $context);
    return $result;
}


