<?php

namespace App\Http\Controllers;

use App\Branch;
use App\College_name;
use App\Events\Notify;
use App\Facematch;
use App\Follow;
use App\Notification;
use Carbon\Carbon;
use Cloudder;
use DB;
use Illuminate\Http\Request;
use Sentinel;

class FacematchController extends Controller
{

    public function index()
    {
        $user = Sentinel::check();

        $check = Facematch::where('user_id', $user->id)->latest()->first();

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

            $followers1 = Follow::where('user_id2', $Fuser1->id)
                ->select('id')->count();
            $following1 = Follow::where('user_id1', $Fuser1->id)
                ->select('id')->count();

            $followers2 = Follow::where('user_id2', $Fuser2->id)
                ->select('id')->count();
            $following2 = Follow::where('user_id1', $Fuser2->id)
                ->select('id')->count();


            $fmatch = Facematch::create([
                'user_id' => $user->id,
                'user_id1' => $Fuser1->id,
                'user_id2' => $Fuser2->id,
                'user_ids' => null,
            ]);

            $img1 = collect($Fuser1)->get('profile_picture_big');

            $college1 = collect($Fuser1)->get('college_name_id');
            $college2 = collect($Fuser2)->get('college_name_id');

            $year1 = collect($Fuser1)->get('college_year');
            $year2 = collect($Fuser2)->get('college_year');

            $branch1 = collect($Fuser1)->get('branch_id');
            $branch2 = collect($Fuser2)->get('branch_id');

            $year1 = Carbon::now()->year - "$year1";
            $year2 = Carbon::now()->year - "$year2";

            $Fuser1 = collect($Fuser1)->put('college_year', "0$year1");
            $Fuser2 = collect($Fuser2)->put('college_year', "0$year2");


            $img2 = collect($Fuser2)->get('profile_picture_big');

            $Fuser1 = collect($Fuser1)->put('college_name_id',
                College_name::where('id', $college1)->select('name')->first()->name);

            $Fuser2 = collect($Fuser2)->put('college_name_id',
                College_name::where('id', $college2)->select('name')->first()->name);

            $Fuser1 = collect($Fuser1)->put('branch_id',
                Branch::where('id', $branch1)->select('name')->first()->name);

            $Fuser2 = collect($Fuser2)->put('branch_id',
                Branch::where('id', $branch2)->select('name')->first()->name);


            $Fuser1 = collect($Fuser1)->put('profile_picture_big', Cloudder::show($img1, array()));
            $Fuser2 = collect($Fuser2)->put('profile_picture_big', Cloudder::show($img2, array()));


            $Fuser2 = collect($Fuser2)->put('followers', $followers2);
            $Fuser2 = collect($Fuser2)->put('following', $following2);
            $Fuser1 = collect($Fuser1)->put('followers', $followers1);
            $Fuser1 = collect($Fuser1)->put('following', $following1);

            $Fuser1 = collect($Fuser1)->put('facematch_id', $fmatch->id);
            $Fuser2 = collect($Fuser2)->put('facematch_id', $fmatch->id);

            $returnUser = array();
            $returnUser["user_1"] = $Fuser1;
            $returnUser["user_2"] = $Fuser2;


            return $returnUser;
        }

        else
            return [];
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'facematch_id' => 'bail|required|integer|exists:facematches,id',
            'face_id' => 'bail|required|integer|exists:users,id',
        ], [
            'facematch_id.*' => 'Invalid request!!',
            'face_id.*' => 'Invalid request!!',
        ]);


        $user = Sentinel::check();

        $facematch_id = $request->facematch_id;
        $face_id = $request->face_id;

        $facematch = Facematch::where('user_id', $user->id)
            ->where('id', $facematch_id)
            ->whereNull('user_ids')->first();


        $user_Selected = null;
        $user_not_Selected = null;

        if ($facematch->user_id1 == $face_id) {
            $user_Selected = $facematch->user_id1;
            $user_not_Selected = $facematch->user_id2;
        } elseif ($facematch->user_id2 == $face_id) {
            $user_Selected = $facematch->user_id2;
            $user_not_Selected = $facematch->user_id1;
        } else {
            return $this->ajaxError('Invalid Request!! ');
        }
        $facematch->update(['user_ids' => $face_id]);
        $this->addNotification($user_Selected, $user_not_Selected, 2, 1);
        $this->addNotification($user_not_Selected, $user_Selected, 2, 0);

        event(new Notify($user_Selected));
        event(new Notify($user_not_Selected));


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

    public function ajaxError($Message)
    {
        return response()->json([
            'error' => [$Message]
        ], 422);

    }

}
