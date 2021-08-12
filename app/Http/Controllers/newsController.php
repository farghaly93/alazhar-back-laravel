<?php

namespace App\Http\Controllers;

use App\Models\news;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
// use Cloudinary\Api\Upload\UploadApi;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider;
use Cloudinary\Asset\Image;
use Cloudinary\Api\Upload;

class newsController extends Controller
{
    public function addNewNews(Request $request) {
        try {
            // $image = $request->input('image');
            // // $folderPath = "/temp/";
            // $path = public_path(). '/';
            // if (!file_exists($path)) {
            //     Storage::makeDirectory($path, $mode = 0777, true, true);
            // }

            // $image_parts = explode(";base64,", $image);
            // $image_type_aux = explode("image/", $image_parts[0]);
            // $image_type = $image_type_aux[1];
            // $img = str_replace(' ', '+', $image_parts[1]);
            // $image_base64 = base64_decode($img);
            // $name = uniqid() . '.'.$image_type;
            // $filePath = $path . $name;
            // file_put_contents($filePath, $image_base64);

            news::create([
                "title" => $request->input("title"),
                "body" => $request->input("body"),
                "image" => $request->input('image'),
                "video" => $request->input('video'),
            ]);
            return response(["updated" => true], 200);

        } catch(Exception $e) {
            return response(["error" => $e], 200);
        }
    }

    public function fetchNews() {
        return response()->json(['news' => News::all()], 200);
    }

    public function getNewsPost(Request $request, $id) {
        $post = DB::table('news')->where("id", $id)->get()->first();
        Date::setLocale("ar");
        $postData = [
            "title" => $post->title,
            "body" => $post->body,
            "image" => $post->image,
            "video" => $post->video,
            "updated_at" => Date::parse($request->updated_at)->format('l j F Y H:i A'),
        ];
        return response()->json(['post' => $postData], 200);
    }

    public function updateNews(Request $request) {
        // return response()->json(['added' => (int)$request->input("id")], 200);
        $image = $request->input('image');
        // return response()->json(["count"], 200);

        // if(strlen($image) > 1000) {
        //     $path = public_path(). '/';
        //     if (!file_exists($path)) {
        //         Storage::makeDirectory($path, $mode = 0777, true, true);
        //     }

        //     $image_parts = explode(";base64,", $image);
        //     $image_type_aux = explode("image/", $image_parts[0]);
        //     $image_type = $image_type_aux[1];
        //     $img = str_replace(' ', '+', $image_parts[1]);
        //     $image_base64 = base64_decode($img);
        //     $name = uniqid() . '.'.$image_type;
        //     $filePath = $path . $name;
        //     file_put_contents($filePath, $image_base64);
        //     $image = "https://al-azhar.herokuapp.com/" . $name;
        // }

        $data = [
            "title" => $request->input('title'),
            "body" => $request->input('body'),
            "image" => $request->input('image'),
            "video" => $request->input('video'),
        ];
        $update = DB::table('news')->where("id", (int)$request->input("id"))->update($data);
        if($update == 1) {
                return response()->json(['updated' => true], 200);
        } else {
            return response()->json(['updated' => false], 200);
        }
    }

    public function deleteNews(Request $request, $id) {
        // return response()->json(["deleted" => $id], 200);
        DB::table('news')->delete((int)$id);
        return response()->json(["deleted" => true], 200);
    }

    public function search(Request $request, $query) {
        $posts = DB::table('news')->where('title', 'LIKE', "%".$query."%");
        if($posts->count() == 0) {
            $posts = DB::table('news')->where('body', 'LIKE', "%".$query."%");
        }
        $posts = $posts->get();
        return response()->json(["posts" => $posts], 200);
    }

    public function filterByDate(Request $request, $date) {
        $posts = DB::table('news')->where('updated_at', '>=',  $date)->get();

        return response()->json(["posts" => $posts], 200);
    }
}


















