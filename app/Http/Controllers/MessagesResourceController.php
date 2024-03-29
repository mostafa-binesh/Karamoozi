<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessagesResource;
use App\Http\Resources\UserMessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class MessagesResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     private $public_path_image = "storage/messages/";
     private $public_path_store = "public/messages";
    public function index(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id'=>'required'
        ]);
        if($val->fails()){
            return response()->json([
                'errors'=>' آیدی فرستنده ارسال نشده است'
            ],400);
        }
        $messages_receive = Message::where('receiver_id', Auth::user()->id)
                            ->where('sender_id',$request->id)->orderBy('created_at')->get();
        $messages_sender = Message::where('receiver_id',$request->id)
                            ->where('sender_id',Auth::user()->id)->orderBy('created_at')->get();
        return[
            'receive'=> MessagesResource::collection($messages_receive),
            'sender'=>MessagesResource::collection($messages_sender),
        ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'users'=>UserMessageResource::collection(User::get()->all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $val = Validator::make($request->all(),[
            'title'=>'required|string|max:255',
            'body'=>'required|string',
            'image'=>'required|image|mimes:png,jpg',
            'receiver_id'=>'required|exists:users,id',
        ]);
        if($val->fails()){
            return response()->json([
                'errors'=>$val->errors(),
            ],400);
        }
        $image_name = time() . '.png';
        $request->file('image')->storeAs( $this->public_path_store ,$image_name);
        Message::create([
            'title'=>$request->title,
            'body'=>$request->body,
            'image'=>$image_name,
            'receiver_id'=>$request->receiver_id,
            'sender_id'=>Auth::user()->id
        ]);
        return response()->json([
            'message'=>'پیام با موفقیت ارسال شد'
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::where('id',$id)->first();

        if(!isset($message->id)){
            return response()->json([
                'errors'=>'پیامی یافت نشد'
            ],404);
        }
        $image_name = $message->image;
        Storage::delete($this->public_path_image . $image_name);
        try {
            unlink(public_path($this->public_path_image . $image_name));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'در حذف فایل مشکلی به وجود آمده است'
            ], 400);
        }
        Message::destroy($message->id);

        return response()->json([
            'message'=>'پیام با موفقیت حذف شد'
        ]);
    }
}
