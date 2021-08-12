<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class locationsController extends Controller
{
    public function addLocation(Request $request) {
        $location = new Location([
            "number" => $request->input("number"),
            "lat" => $request->input("lat"),
            "lng" => $request->input("lng"),
            "desc" => $request->input("desc"),
        ]);

        $location->save();
        if($location->count() > 0) {
            return response()->json(["added" => true], 201);
        }
    }

    public function getLocations() {
        $locations = Location::all();
        return response()->json(["locations" => $locations], 200);
    }

    public function updateLocation(Request $request) {
        $update = DB::table('locations')->where("id", (int)$request->input("id"))->update($request->input());
        if($update == 1) {
            return response()->json(["added" => true], 200);
        }
    }

    public function deleteLocation(Request $request, $id) {
        $del = DB::table('locations')->delete($id);
        if($del == 1) {
            return response()->json(["deleted" => true], 200);
        }
    }
}
