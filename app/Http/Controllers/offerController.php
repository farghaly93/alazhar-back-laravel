<?php

namespace App\Http\Controllers;

use App\Models\offer;
use DateTime;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Offer as GlobalOffer;
use Tymon\JWTAuth\Http\Parser\QueryString;

class offerController extends Controller
{
    public function addOffer(Request $request) {
        $offer = new Offer([
            "title" => $request->input('title'),
            "activity" => $request->input('activity'),
            "type" => $request->input('type'),
            "area" => $request->input('area'),
            "price" => $request->input('price'),
            "site" => $request->input('site'),
            "negotiable" => true,
            "desc" => $request->input('desc'),
            "lat" => $request->input('lat'),
            "lng" => $request->input('lng'),
            "name" => $request->input('name'),
            "phone" => $request->input('phone'),
            "confirmed" => $request->input('confirmed'),
        ]);

        $offer->save();
        return response()->json(['added' => true], 201);
    }

    public function addClientOffer(Request $request) {
        $offer = new Offer([
            "title" => $request->input('title'),
            "activity" => $request->input('activity'),
            "type" => $request->input('type'),
            "area" => $request->input('area'),
            "price" => $request->input('price'),
            "site" => $request->input('site'),
            "negotiable" => true,
            "desc" => $request->input('desc'),
            "lat" => $request->input('lat'),
            "lng" => $request->input('lng'),
            "name" => $request->input('name'),
            "phone" => $request->input('phone'),
            "confirmed" => false,
        ]);

        $offer->save();
        return response()->json(['added' => true], 201);
    }

    public function fetchOffers(Request $request) {
        $offs = Offer::get();
        $offers = DB::table('offers')->get();
        // $modified = [];
        // foreach (collect($offers) as $key => $val) {
        //     foreach($offers[$key] as $k => $v) {
        //         $modified[$key][$k] = $v;
        //     }
        // }
        // print_r($offers);
        // $modified = (array)array_map(array($this, 'modifyOffers'), array($offers));
        return response()->json(["offers" => $offers], 200);
    }

    public function getoffer(Request $request, $id) {
        $offer = DB::table('offers')->where('id', $id)->first();
        return response(['offer' => $offer], 200);
    }

    public function updateOffer(Request $request) {
        $data = [
            "title" => $request->input('title'),
            "activity" => $request->input('activity'),
            "type" => $request->input('type'),
            "area" => $request->input('area'),
            "price" => $request->input('price'),
            "site" => $request->input('site'),
            "negotiable" => true,
            "desc" => $request->input('desc'),
            "lat" => $request->input('lat'),
            "lng" => $request->input('lng'),
            "confirmed" => $request->input('confirmed'),
        ];

        // $offer = offer::find($request->id);
        // $offer = (object)$data;
        // $offer->save();
        // $request->only('title', 'desc', 'activity', 'type', 'area', 'price', 'negotiable'. 'site', 'lat', 'lng')
        $update = DB::table('offers')->where('id', (int)$request->input('id'))->update($request->input());

        $offer = DB::table('offers')->find($request->id);
        // dd($offer);
        return response()->json(['added' => true], 200);
    }

    public function fetchFilters($type) {
        $offers = DB::table('offers');
        if($type == 'published') $offers->where("confirmed", 1);
        else if($type == 'suspended') $offers->where("confirmed", 0);
        $activityFilters = $offers->select(DB::raw('activity as value, count(*) as count'))
                    ->groupBy('activity')
                    ->get();
        $typeFilters = $offers->select(DB::raw('type as value, count(*) as count'))
                    ->groupBy('type')
                    ->get();
        $siteFilters = $offers->select(DB::raw('site as value, count(*) as count'))
                    ->groupBy('site')
                    ->get();
        return response()->json(['filters'=>(["activity"=>$activityFilters, "type"=>$typeFilters, "site"=>$siteFilters])], 200);
    }

    public function filterOffers(Request $request, $skip, $limit, $type) {
        $activityArray = $request->input('activity');
        $typeArray = $request->type;
        $siteArray = $request->site;
        $area = $request->area;
        $price = $request->price;
        $search = $request->like;
        $date = $request->date;
        $offers = DB::table('offers');
        $searchCount = 0;
        if($type == "published") {
            $offers = $offers->where("confirmed", 1);
        }
        else if($type == "suspended") {
            $offers = $offers->where("confirmed", 0);
        }

        if($search != '') {
            $offers = $offers->where("phone", "LIKE", '%'.$search.'%');
            $searchCount = $offers->count();
            if($offers->count() < 1) {$offers = DB::table('offers')->where("type", "LIKE", '%'.$search.'%');}
            if($offers->count() < 1) {$offers = DB::table('offers')->where("site", "LIKE", '%'.$search.'%');}
            if($offers->count() < 1) {$offers = DB::table('offers')->where("desc", "LIKE", '%'.$search.'%');}
            if($offers->count() < 1) {$offers = DB::table('offers')->where("title", "LIKE", '%'.$search.'%');}
            if($offers->count() < 1) {$offers = DB::table('offers')->where("name", "LIKE", '%'.$search.'%');}
            if($offers->count() < 1) {$offers = DB::table('offers')->where("activity", "LIKE", '%'.$search.'%');}
                            // ->orWhere("type", "LIKE", '%'.$search.'%')
                            // ->orWhere("site", "LIKE", '%'.$search.'%')
                            // ->orWhere("desc", "LIKE", '%'.$search.'%')
                            // ->orWhere("title", "LIKE", '%'.$search.'%');
                            // ->orWhere("name", "LIKE", '%'.$search.'%');
                            // ->orWhere("phone", "LIKE", '%'.$search.'%');

        }

        if(count($activityArray) > 0) {
           $offers=  $offers->whereIn('activity', $activityArray);
        }
        if(count($typeArray) > 0) {
            $offers=  $offers->whereIn('type', $typeArray);
        }
        if(count($siteArray) > 0) {
        $offers=  $offers->whereIn('site', $siteArray);
        }
        if($area > 0) {
            $offers = $offers->where("area", "<=", $area);
        }
        if($price > 0) {
            $offers = $offers->where("price", "<=", $price);
        }
        if($date != '') {
            $offers = $offers->where("updated_at", ">=", $date);
        }

        $countAll = $offers->count();

        $offers = $offers->skip($skip)->take($limit)->get();

        $modified = collect($offers)->map(function($collection, $key) {
            $col = (object) $collection;
            return [
                "id" => $col->id,
                "title" => $col->title,
                "activity" => $col->activity,
                "type" => $col->type,
                "area" => $col->area,
                "price" => $col->price,
                "site" => $col->site,
                "negotiable" => $col->negotiable,
                "desc" => $col->desc,
                "lat" => $col->lat,
                "lng" => $col->lng,
                "name" => $col->name,
                "phone" => $col->phone,
                "confirmed" => $col->confirmed,
                "updated_at" =>  date('d-m-Y/h:m A', strtotime($col->updated_at)),
                // "updated_at" =>  $col->updated_at ->date_diff(new DateTime()),
            ];
        });

        return response()->json(["offers"=>$modified, 'numberOfAllItems'=>$countAll, "searchCount"=>$searchCount], 200);
    }

    public function deleteOffer(Request $request, $id) {
        DB::table('offers')->delete($id);
        return response(['deleted' => true], 200);
    }
}
