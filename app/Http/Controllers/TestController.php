<?php

namespace App\Http\Controllers;

use App\Http\Resources\chats\message as ChatsMessage;
use App\Http\Resources\chats\receive;
use App\Http\Resources\chats\send;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserPaginationResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Hekmatinasser\Verta\Verta;

class TestController extends Controller
{
    public function usersPagination(Request $req)
    {
        // return User::paginate(1);
        // return new UserPaginationResource(User::paginate(1));
        return response()->json(new UserPaginationResource(User::paginate(1)), 200);
        // return response()->json(User::paginate(1),200);
        // return new UserPaginationResource(User::find(1));

    }
    public function enum_test()
    {
        return User::find(2)->student->it();
    }
    public function verta()
    {
        // if entered day wasn't saturday, look for next saturday
        $datetime = verta('2023-01-7');
        // echo $datetime->addDay()->addDay()->addDay();
        // echo $datetime->addDay()->addDay();
        echo $datetime->addDays(3);
        if ($datetime->dayOfWeek != 0) {
        }
        // if now friday
        // echo ;
    }



    public function sender($id)
    {
        $student = Chat::where("sender_id", $id)->get();
        return send::collection($student);
    }
    public function receive($id)
    {
        $student = Chat::where("receiver_id", $id)->get();
        return receive::collection($student);
    }

    public function create_chat(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required',
            'body' => 'required',
            'receiver' => 'different:sender',
            'sender' => 'different:receiver',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $chat = Chat::create([
            'title' => $req->title,
            'receiver_id' => $req->recevier_id,
            'sender_id' => $req->sender_id,
        ]);
        Message::create([
            'chat_id' => $chat->id,
            'body' => $req->body,
            'sender_id' => $req->sender_id,
        ]);
        return response()->json([
            'message' => 'مکاتبه ایجاد شد',
        ]);
    }
    public function create_message(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'body' => 'required',
            'receiver' => 'different:sender',
            'sender' => 'different:receiver',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        Chat::create([
            'body' => $req->body,
            'receiver_id' => $req->recevier_id,
            'sender_id' => $req->sender_id,
            'chat_id' => $req->chat_id
        ]);
        return response()->json([
            'message' => 'مکاتبه ایجاد شد',
        ]);
    }

    public function get_message($id)
    {
        $messages = Message::where('chat_id', $id)->get();
        return message::collection($messages);
    }
}
