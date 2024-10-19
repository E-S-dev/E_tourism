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

//APPLAY FOR TOURS-------------------------------------------------------------------------------------

    function applyForTour($tour_id){

        $tour = Tour::find($tour_id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found!'], 404);
        }

        if($tour->status == 'closed'){
            return response()->json([
                'message' => 'You can not applay for this tour, it is closed!'
            ]);
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

        $tour->number -= 1;

        if($tour->number == 0){
            $tour->status = 'closed';
            $tour->save();
        }

        $tour->save();

        return response()->json([
            'message' => 'You have regesterd for this tour suucessfully.',
            'tourist' => $tourist,
            'tour' => $tour,
        ],200);
    }


//GET TOURIST'S TOUR------------------------------------------------------------------------------------
    function getMyTour($id){

        $tourist = Tourist::find($id);

        if(!$tourist){
            return response()->json([
                'message' => 'your are not a tourists'
            ]);
        }

        $tour = Tour::find($tourist->tour_id);

        if(!$tour){
            return response()->json([
                'message' => 'you dont have any tour!'
            ]);
        }

        return response()->json([
            'tours' => $tour
        ]);
    }
}
