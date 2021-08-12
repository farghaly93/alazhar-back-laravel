<?php

namespace App\Http\Controllers;

use App\Models\info;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InfoController extends Controller
{
    public function updateInfo(Request $request) {
        // $info =  DB::table('infos')->get();
        $count = info::all()->count();
        $fields = (array)$request->input();
        $features = (array)["features" => implode("/", $request->input('features'))];
        $arr = array_merge($fields, $features);

        if($count == 0) {
            $info = new info($arr);
            $info->save();
            return response()->json(["updated" => true], 201);
        } else {
            $update = DB::table('infos')->update($arr);
            if($update == 1) {
                return response()->json(["updated" => true], 200);
            }
        }
        // return response()->json(["info" => $info], 200);
    }

    public function fetchInfo() {
        $info = info::all()->first();
        return response()->json(["info" => $info], 200);
    }

    public function sendMessage(Request $request) {
        $mess = new Message(["name" => $request->input("name"), "message" => $request->input("message")]);
        $mess->save();
        // if($mess->count() > 0) {
            return response()->json(["sent" => true], 201);
        // }
    }

    public function fetchMessages() {
        $messages = Message::all();
        return response()->json(["messages" => $messages], 200);
    }

    public function deleteMessage(Request $request, $id) {
        $del = DB::table('messages')->delete($id);
        if($del) {
            return response()->json(["deleted" => true], 200);
        } else {
            return response()->json(["deleted" => false], 200);
        }
    }
}
