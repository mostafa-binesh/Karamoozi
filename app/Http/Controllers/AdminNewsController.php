<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Repositories\NewsRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminNewsController extends Controller
{
    private $news;
    public function __construct(NewsRepo $news)
    {
        $this->news = $news;
    }
    private $public_path_image = "storage/news/";
    private $public_path_store = "public/news";
    private function delete_image($image_name)
    {
        Storage::delete($this->public_path_image . $image_name);
        try {
            unlink(public_path($this->public_path_image . $image_name));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'در حذف فایل مشکلی به وجود آمده است'
            ], 400);
        }
    }

    public function index(Request $req)
    {
        try {
            return $this->news->paginat($req);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            //! Validation
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

            //! image name
            $imageName = 'news-' . time() . '.png';

            //! create news
            $this->news->create([
                'title' => $request->title,
                'body' => $request->body,
                'image' => $imageName,
            ]);

            //! store image news
            $request->file('image')->storeAs($this->public_path_store, $imageName);

            return response()->json([
                'succses' => 'خبر با موفقیت اضافه شد',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            return $this->news->getById($id);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        return $this->show($id);
    }

    public function  updata_news (Request $request , $id){
         //! Validation
         $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100|unique:news,title,' . $id,
            'body' => 'required|string',
            'image'=>'image'
        ]);

        // return $request->image;
        if ($val->fails()) {
            return response()->json([
                'error' => $val->errors(),
            ], 400);
        }
        //! Found news
        $newsUpdate = $this->news->getById($id);
        if ($newsUpdate == false) {
            return response()->json([
                'error' => "خبر یافت نشد",
            ], 404);
        }
        if($request->image) {

            if($newsUpdate->image){
                $this->delete_image($newsUpdate->image);
            }

            $imageName = 'news-' . time() . '.png';

            $request->file('image')->storeAs($this->public_path_store, $imageName);

            $newsUpdate->update(['image'=>$imageName]);
        }
        //! update news
        $newsUpdate->update([
            'title'=>$request->title,
            'body'=>$request->body
        ]);

        return response()->json([
            'messagw' => 'خبر با موفقیت ویرایش شد'
        ], 200);

    }
    public function update(Request $request, $id)
    {
        return;
    }

    public function destroy($id)
    {
        try {
            //! check to exist news
            $news = $this->news->getById($id);
            if ($news == false) {
                return response()->json([
                    'error' => "خبر یافت نشد",
                ], 404);
            }
            //!delete image news

            $this->delete_image($news->image);

            //!delete news
            $this->news->delete($id);

            return response()->json([
                'message' => 'خبر با موفقیت حذف شد'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

}
