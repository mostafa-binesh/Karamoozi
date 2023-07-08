<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\ModelFilters\NewsFilter;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        return News::filter($req->all(), NewsFilter::class)->cpagination($req, NewsResource::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100|unique:news,title',
            'body' => 'required|string',
            'image' => 'file|required|mimes:png,jpg,jpeg'
        ]);
        if ($val->fails()) {
            return response()->json([
                'error' => $val->errors(),
            ], 400);
        }

        $imageName = time() .'.png';
        $request->file('image')->storeAs('public/news', $imageName);

        News::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imageName,
        ]);

        return response()->json([
            'succses' => 'خبر با موفقیت اضافه شد',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::where('id', $id)->first();
        if (!isset($news->id)) {
            return response()->json([
                'error' => 'خبری یافت نشد'
            ], 400);
        }
        return response()->json([
            'data' => $news,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news = News::where('id', $id)->first();
        if (!isset($news->id)) {
            return response()->json([
                'error' => 'خبری یافت نشد'
            ], 400);
        }
        return response()->json([
            'data' => $news,
        ], 200);
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
        $news = News::where('id', $id)->first();
        if (!isset($news->id)) {
            return response()->json([
                'error' => 'خبری یافت نشد'
            ], 400);
        }
        return response()->json([
            'data' => $news,
        ], 200);

        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100|unique:news,title' . $news->id,
            'body' => 'required|string',
            //'image'=>'file|required|mimes:png,jpg,jpeg'
        ]);

        if ($request->image != null) {
            Storage::delete('storage/news/' . $news->image);
            $imageName = time() . '.png';
            $request->file('image')->storeAs('public/news', $imageName);
            $news->image= $imageName;
        }
        $news->title = $request->title;
        $news->body = $request->body;
        $news->save();

        return response()->json([
            'sucsses'=>'خبر با موفقیت ویرایش شد'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::where('id',$id)->first();
        if(!isset($news->id)){
            return response()->json([
                'error'=> 'خبری یافت نشد'
            ],400);
        }
        Storage::delete('storage/news/' . $news->image);
        News::destroy($news->id);

        return response()->json([
            'sucsses'=>'خبر با موفقیت حذف شد'
        ],200);
    }
}
