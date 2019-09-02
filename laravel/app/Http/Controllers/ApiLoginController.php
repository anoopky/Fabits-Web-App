<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\oAuth;
use App\Optional;
use App\Privacy;
use App\Sms;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Support\Facades\Validator;
use Cloudder;
use Carbon\Carbon;

use Sentinel;


class ApiLoginController extends Controller
{

    public function createUser(Request $request)
    {


        try {

            $validator = Validator::make($request->all(), [
                'username' => 'bail|required|alpha_dash|max:20',
                'password' => 'bail|required|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 200);
            }

            $username = $request->username;
            $password = $request->password;


            $remember = false;

            Sentinel::authenticate(array(
                'username' => $username,
                'university_id' => $username,
                'phone' => $username,
                'college_id' => $username,
                'password' => $password,
            ), $remember);

            if ($user = Sentinel::check()) {

                $token = md5(uniqid() . $user->id);
                oAuth::create([
                    'user_id' => $user->id,
                    'token' => $token,
                    'device_id' => "1",
                ]);

                return $this->loginResponse($user, $token);

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

                            $token = md5(uniqid() . $user->id);
                            oAuth::create([
                                'user_id' => $user->id,
                                'token' => $token,
                                'device_id' => "1",
                            ]);

                            return $this->loginResponse($user, $token);


                        } else {
                            Sentinel::logout();
                            return $this->JsonMessage("error", 'Invalid Password!! ');
                        }
                    } else {
                        Sentinel::logout();
                        return $this->JsonMessage("error", 'Invalid Password!! ');
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

                                return $this->JsonMessage("error", 'You have changed your password !');
                            }

                            $userthis = Sentinel::authenticate(array(
                                'username' => $userPSIT_rollno,
                                'password' => $password,
                            ), $remember);


                            if ($user = Sentinel::check()) {

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

                                $token = md5(uniqid() . $user->id);
                                oAuth::create([
                                    'user_id' => $user->id,
                                    'token' => $token,
                                    'device_id' => "1",
                                ]);

                                return $this->loginResponse($user, $token);


                            } else {

                                return $this->JsonMessage("error", 'Server Error !! ');
                            }

                        } else {

                            return $this->JsonMessage("error", 'Invalid Password!! ');

                        }

                    } else {

                        return $this->JsonMessage("error", 'Can\'t find you in fabits.in or in psit.in !! ' . $helpMessage);
                    }
                }
            }

        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return $this->JsonMessage("error", "You are banned for $delay seconds.");
        }
    }

    public function loginResponse($user, $token)
    {


        $jsonURL = array(
            '_0' => 'posts',
            '_1' => 'messagesList',
            '_2' => 'like',
            '_3' => 'trend',
            '_4' => 'online',
            '_5' => 'likes',
            '_6' => 'comment',
            '_7' => 'my_following',
            '_8' => 'newMessage',
            '_9' => 'simple_message',
            '_10' => 'complex_message',
            '_11' => 'postText',
            '_12' => 'postImage',
            '_13' => 'readConversation',
            '_14' => 'notification',
            '_15' => 'randomProfiles',
            '_16' => 'settings',
            '_17' => 'conversationInit',
            '_18' => 'online_conversation',
            '_19' => 'postSingle',
            '_20' => 'search',
            '_21' => 'postsPool',
            '_22' => 'forceReadConversation',
            '_23' => 'typing',
            '_24' => 'conversationDelete',
            '_25' => 'chatImageUpload',
            '_26' => 'deletePost',
            '_27' => 'unFollowPost',
            '_28' => 'deleteComment',
            '_29' => 'follow',
            '_30' => 'checkFaceMatch',
            '_31' => 'changePassword',
            '_32' => 'contactUs',
            '_33' => 'logout',
            '_34' => 'changePhoneNumber',
            '_35' => 'Otp',
            '_36' => 'chatAllow',
            '_37' => 'block',
            '_38' => 'my_blocks',
            '_39' => 'my_block_list',
            '_40' => 'chatBlock',
            '_41' => 'bigImage',
            '_42' => 'profileCountLists',
            '_43' => 'newNotification',
            '_44' => 'profilePic',
            '_45' => 'profileWall',
            '_46' => 'suggestion',
            '_47' => 'signUpPassword',
            '_48' => 'signUpPasswordSkip',
            '_49' => 'signUpGenderDob',
            '_50' => 'signUpProfilePicSkip',
            '_51' => 'signUpProfilePic',

//                    UPDATE users
//SET `wall_picture_small` = CONCAT('http://res.cloudinary.com/fabits-in/image/upload/v1/', `wall_picture_small`, '.jpg' )
        );


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

        if ($relationship == 1)
            $relationship = "Single";
        else if ($relationship == 2)
            $relationship = "Committed";
        else if ($relationship == 3)
            $relationship = "Complicated";

        $jsonSettings = array(
            'S_USERNAME' => $user->username,
            'S_STATUS' => $intro,
            'S_EMAIL' => $email,
            'S_FACEBOOK' => $facebook,
            'S_WHATSAPP' => $whatsapp,
            'S_LOCATION' => $location,
            'S_RELATIONSHIP' => $relationship,
            'S_PHONE' => $phone,
            'S_P_PHONE' => $privacy["phone"] == 0 ? "Private" : "Public",
            'S_P_FOLLOWERS' => $privacy["followers"] == 0 ? "Private" : "Public",
            'S_P_FOLLOWING' => $privacy["following"] == 0 ? "Private" : "Public",
            'S_P_FACEMATCH' => $privacy["facematch"] == 0 ? "Private" : "Public",
            'S_A_LOGIN' => $notification["login"] == 0 ? "Yes" : "No",
            'S_A_MESSAGE' => $notification["message"] == 0 ? "Yes" : "No",
            'S_A_NOTIFICATION' => $notification["notification"] == 0 ? "Yes" : "No",
            'S_A_ANONY_MESSAGE' => $notification["anonymous"] == 0 ? "Yes" : "No",
        );

        $json = json_encode(array(
            'ID' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'profile_picture_small' => Cloudder::show($user->profile_picture_small, array()),
            'urls' => $jsonURL,
            'settings' => $jsonSettings,
            'baseUrl' => "http://fabits.in/api/",
            'status' => $user->status,
            'token' => $token,
        ));

        return $this->JsonMessage("success", $json);


    }


    public function JsonMessage($status, $Message)
    {
        $json = json_encode(array(
            $status => $Message,
        ));

        return $json;
    }
}


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