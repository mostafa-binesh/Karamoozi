<?php

namespace App\Http\Controllers;


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

    public function update(Request $request, $id)
    {
        // try {
            //! Validation
            $val = Validator::make($request->all(), [
                'title' => 'required|string|max:100|unique:news,title,' . $id,
                'body' => 'required|string'
            ]);
            if ($val->fails()) {
                return response()->json([
                    'error' => $val->errors(),
                ], 400);
            }

            //! Found news
            $news = $this->news->getById($id);
            if ($news == false) {
                return response()->json([
                    'error' => "خبر یافت نشد",
                ], 404);
            }

            //! update news
            $this->news->update($request, $news);

            return response()->json([
                'messagw' => 'خبر با موفقیت ویرایش شد'
            ], 200);

        // }
        //  catch (\Exception $e) {
        //     return response()->json([
        //         "error" => $e->getMessage(),
        //     ],400);
        // }
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

    public function updateImage(Request $request, $id)
    {
        try {
            $news = $this->news->getById($id);
            if ($news == false) {
                return response()->json([
                    'error' => "خبر یافت نشد",
                ], 404);
            }

            $imageName = 'news-' . time() . '.png';
            $request->file('image')->storeAs($this->public_path_store, $imageName);
            $news->image = $imageName;
            $news->save();
            return response()->json([
                'message' => 'تصویر خبر با موفقیت ادیت شد'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

    public function destroyImage(Request $request, $id)
    {
        try {
            //! found news
            $news = $this->news->getById($id);
            if ($news == false) {
                return response()->json([
                    'error' => "خبر یافت نشد",
                ], 404);
            }

            //! delete image news
            $this->delete_image($news->image);
            $news->image = null;
            $news->save();
            return response()->json([
                "message" => "تصویر خبر حذف شد"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }
}
