<?php
namespace App\Http\Controllers;

use App\Block;
use App\Branch;
use App\College_name;
use App\Comment;
use App\Conversation;
use App\Conversationv2;
use App\Events\OnlineConversation;
use App\Events\SeenReadConversation;
use App\Events\TypingV2;
use App\Facematch;
use App\Follow;
use App\GroupList;
use App\Hashtag;
use App\Message;
use App\MessageV2;
use App\Otp;
use App\Post_data;
use App\Profileview;
use App\Relationship;
use App\Reports;
use Illuminate\Http\Request;
use App\oAuth;
use App\Optional;
use App\Privacy;
use App\SMS;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cloudder;
use Sentinel;
use Carbon\Carbon;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use App\Like;
use App\Notification;
use App\Post;
use App\UnfollowPost;
use DB;

class Fabitsapi extends Controller
{



    public function postSingle(Request $request, $token, $postID)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $postAll = Post::where('id', $postID)->get();

            return $this->getPosts($postAll, $user);
        }

        return "-1";
    }


    public function postsControl(Request $request, $token, $operator, $postID)
    {

        $user = oAuth::where([
            'token' => $token,
        ])->first();

        if ($user) {

            $op = null;
            if ($operator == "lt")
                $op = "<";
            else
                $op = ">";

            $postAll = Post::where('id', $op, $postID)
                ->latest()
                ->offset(0)
                ->limit(5)
                ->get();

            return $this->getPosts($postAll, $user);
        }

        return "-1";
    }


    public function trending(Request $request)
    {
        if ($request->ajax()) {

            $TrendingPosts = Like::groupBy('post_id')
                ->select('post_id', DB::raw('count(post_id) as total'))
                ->where('created_at', '>=', Carbon::now()->subDays(1))
                ->orderby(DB::raw('total'), 'DESC')
                ->get();


            $postID = array();

            foreach ($TrendingPosts as $TrendingPost) {
                array_push($postID, $TrendingPost->post_id);
            }

            $postIDString = implode(",", $postID);

            $postAll = Post::wherein('id', $postID)
                ->orderby(DB::raw("FIELD(id, $postIDString)"))
                ->paginate(5);

            return $this->getPosts($postAll);

        } else {
            return redirect('/home');
        }
    }

    public function date_small($Date)
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
            return substr($text, 0, $len) . "...";
        } else
            return $text;

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






}

// -----------------get data from PSIT-------------------------------//




//

