<?php

namespace App\Providers;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Storage;

class FileProvider
{
    private $file_path = "";

    private $file_name = "";

    public function create_name()
    {
        $file_name = time() . ".png";

        return $file_name;
    }

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    public function getFilePath()
    {
        return $this->file_path;
    }

    public function getFileName()
    {
        return $this->file_name;
    }
    public function StrogeFile(Request $request)
    {
        try {
            $request->file('image')->storeAs("public/" . $this->file_path, $this->file_name);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function delete_image($image_name)
    {
        Storage::delete("storage/" . $this->file_path . $image_name);
        try {
            unlink(public_path("storage/" . $this->file_path . $image_name));
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}