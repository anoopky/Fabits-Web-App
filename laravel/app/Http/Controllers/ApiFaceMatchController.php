<?php

namespace App\Http\Controllers;


use App\Facematch;
use Illuminate\Http\Request;
use App\oAuth;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use App\Notification;
use DB;


class ApiFaceMatchController extends Controller
{

    public function checkFaceMatch(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $userID = $user->user_id;

            $user = Sentinel::findById($userID);


            $check = Facematch::where('user_id', $userID)->latest()->first();

            $carbon = 24;
            if (count($check))
                $carbon = Carbon::now()->diffInHours(Carbon::parse($check->created_at));

            if ($carbon >= 24) {

                $result = DB::table('users')
                    ->where('id', '!=', $user->id)
                    ->where('branch_id', '=', $user->branch_id)
                    ->where('college_year', '=', $user->college_year)
                    ->where('college_name_id', '=', $user->college_name_id)
                    ->select('id', 'name', 'username', 'profile_picture_big', 'college_year', 'college_name_id', 'branch_id')
                    ->get();
                $Fuser1 = '';
                $Fuser2 = '';
                do {
                    $i = 0;
                    $users = collect($result)->random(2)->all();
                    foreach ($users as $ruser) {
                        if ($i == 0)
                            $Fuser1 = $ruser;
                        else
                            $Fuser2 = $ruser;
                        $i++;
                    }
                    $resultcheck = Facematch::where([
                        ['user_id', $user->id],
                        ['user_id1', $Fuser1->id],
                        ['user_id2', $Fuser2->id],
                    ])->orwhere([
                        ['user_id', $user->id],
                        ['user_id1', $Fuser2->id],
                        ['user_id2', $Fuser1->id],
                    ])
                        ->get();
                } while (count($resultcheck));
                $fmatch = Facematch::create([
                    'user_id' => $user->id,
                    'user_id1' => $Fuser1->id,
                    'user_id2' => $Fuser2->id,
                    'user_ids' => null,
                ]);
                $img1 = collect($Fuser1)->get('profile_picture_big');
                $img2 = collect($Fuser2)->get('profile_picture_big');
                $Fuser1 = collect($Fuser1)->put('profile_picture_big', Cloudder::show($img1, array()));
                $Fuser2 = collect($Fuser2)->put('profile_picture_big', Cloudder::show($img2, array()));

                $Fuser1 = collect($Fuser1)->put('fid', $fmatch->id);
                $Fuser2 = collect($Fuser2)->put('fid', $fmatch->id);

                $returnUser = array();
                $returnUser["user_1"] = $Fuser1;
                $returnUser["user_2"] = $Fuser2;


                return $returnUser;
            } else
                return [];
        }
    }



    public function FaceMatchUpdate(Request $request, $token)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {
            $user = $user->user_id;
            $facematch_id = $request->fid;
            $face_id = $request->userID;

            $facematch = Facematch::where('user_id', $user)
                ->where('id', $facematch_id)
                ->whereNull('user_ids')->first();


            $user_Selected = null;
            $user_not_Selected = null;

            if ($facematch->user_id1 == $face_id) {
                $user_Selected = $facematch->user_id1;
                $user_not_Selected = $facematch->user_id2;
            } elseif
            ($facematch->user_id2 == $face_id
            ) {
                $user_Selected = $facematch->user_id2;
                $user_not_Selected = $facematch->user_id1;
            } else {
                return 'Invalid Request!! ';
            }
            $facematch->update(['user_ids' => $face_id]);


            $this->addNotification($user_Selected, $user_not_Selected, 2, 1);
            $this->addNotification($user_not_Selected, $user_Selected, 2, 0);
        }


    }




    public function addNotification($user_id, $source_id, $type, $activity_type)
    {

        Notification::create([
            'user_id' => $user_id,
            'source_id' => $source_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ]);

    }

    public function removeNotification($user_id, $source_id, $type, $activity_type)
    {

        Notification::where([
            'user_id' => $user_id,
            'source_id' => $source_id,
            'type' => $type,
            'activity_type' => $activity_type,
        ])->delete();
    }



    //
}
