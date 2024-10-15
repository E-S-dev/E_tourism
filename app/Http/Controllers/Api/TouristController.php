<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use App\Models\Tour;
use App\Http\Requests\StoreTouristRequest;
use App\Http\Requests\UpdateTouristRequest;

class TouristController extends Controller
{
    //GET ALL TOURISTS FUNCTION----------------------------------------------------------------------------

    function getTourists(Tourist $tourists){

        return $tourists->all();

    }

    function applyForTour($tour_id){

        $tour = Tour::find($tour_id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found!'], 404);
        }

        $user = auth()->user();
        $tourist = Tourist::where('id', $user->tourist_id)->first();

        if(!$tourist){
            return response()->json(['message' => 'Tourist not found!'], 404);
        }

        if($tourist->tour_id == $tour->id){
            return response()->json(['message' => 'You are already registerd for this Tour!'], 400);
        }

        $tourist->tour_id = $tour->id;
        $tourist->save();

        $tour->number += 1;
        $tour->save();

        return response()->json([
            'message' => 'You have regesterd for this tour suucessfully.',
            'tourist' => $tourist,
            'tour' => $tour,
        ],200);
    }
}
