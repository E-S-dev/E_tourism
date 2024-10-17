<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Support\Facades\Validator;
use App\Models\Guide;
use App\Models\Driver;
use App\Models\Programme;

class TourController extends Controller
{

//GET ALL TOURS FUNCTIONS--------------------------------------------------------------------

    function getTours(Tour $tours){

        return $tours->all();

    }

    function getOpenTours(){

        $tours = Tour::where('status', 'open')->get();
        return $tours;

    }

    function getTourDetails($id){
        $tour = Tour::find($id);
        if(!$tour){
            return response()->json([
                'message' => 'Tour not found!'
            ]);
        }
        return response()->json([
            'tour' => $tour
        ]);
    }

//Add Tour Function----------------------------------------------------------------------

    function addTour(Request $request){

        $validator = Validator::make($request->all(),[
            'guide_id' => 'required|exists:guides,id',
            'driver_id' => 'required|exists:drivers,id',
            'programme_id' => 'required|exists:programmes,id',
            'photo' => 'image|mimes:png,jpg|max:2048',
            'price' => 'required|numeric|between:0,9999.99',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
            'number' => 'required|unique:tours,number|between:0,6',
            'description' => 'required|max:255',
        ],[
            //messages for Errores...............................

            'guide_id.exists' => 'Guide not found.',
            'driver_id.exists' => 'Driver not found.',
            'programme_id.exists' => 'Programme not found.',
            'number.unique' => 'Namber already exists.',
            'date.date_format' => 'Please write the date in this format "YYYY-MM-DD.'

        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $guide = Guide::find($request->guide_id);
        $driver = Driver::find($request->driver_id);
        $programme = Programme::find($request->programme_id);

        $tour = new Tour();
        if($request->hasFile('photo')){
            $photoPath = $request->file('photo')->store('photos', 'public');
            $tour->photo = $photoPath;
        }

        $tour->guide_id = $guide->id;
        $tour->driver_id = $driver->id;
        $tour->programme_id = $programme->id;
        $tour->price = $request->price;
        $tour->startDate = $request->startDate;
        $tour->endDate = $request->endDate;
        $tour->number = $request->number;
        $tour->description = $request->description;

        $tour->save();
        $tour->refresh();
        return response()->json([
            'message' => 'Tour added successfully.',
            'tour' => $tour,
        ],201);
    }

//Update Tour Function------------------------------------------------------------------------

    function updateTour(Request $request, $id){

        $validator = Validator::make($request->all(),[
            'guide_id' => 'required|exists:guides,id',
            'driver_id' => 'required|exists:drivers,id',
            'programme_id' => 'required|exists:programmes,id',
            'photo' => 'image|mimes:png,jpg|max:2048',
            'price' => 'required|numeric|between:0,9999.99',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
            'number' => 'required|between:0,6',
            'description' => 'required|max:255',
        ],[
            //messages for Errores...............................

            'guide_id.required' => 'Please select a guide.',
            'driver_id.required' => 'Please select a driver.',
            'programme_id.required' => 'Please select a programme.',
            'guide_id.exists' => 'Guide not found.',
            'driver_id.exists' => 'Driver not found.',
            'programme_id.exists' => 'Programme not found.',
            'date.date_format' => 'Please write the date in this format "YYYY-MM-DD.'

        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $tour = Tour::find($id);

        if(!$tour){
            return response()->json([
                'message' => 'Tour not found!'
            ]);
        }

        if($request->hasFile('photo')){
            $photoPath = $request->file('photo')->store('photos', 'public');
            $tour->photo = $photoPath;
        }

        $tour->guide_id = $request->guide_id;
        $tour->driver_id = $request->driver_id;
        $tour->programme_id = $request->programme_id;
        $tour->price = $request->price;
        $tour->status = $request->status;
        $tour->startDate = $request->startDate;
        $tour->endDate = $request->endDate;
        $tour->number = $request->number;
        $tour->description = $request->description;

        $tour->save();
        $tour->refresh();

        return response()->json([
            'message' => 'Tour updated successfully.',
            'tour' => $tour,
        ]);

    }

//DELETE TOUR FUNCTION----------------------------------------------------------------------------

    function destroyTour(Request $request, $id){

        $tour = Tour::find($id);

        if(!$tour){return response()->json(['message' => 'Tour Not Found!.']);}

        $tour->delete();

        return response()->json([
            'message' => 'Tour Deleted Successfully!'
        ]);
    }

    function searchTours(Request $request){

        $searchTerm = $request->keyword;
        if(!$searchTerm){
            return response()->json(['message' => 'Please enter a keyword!'], 400);
        }
        $tours = Tour::where('description', 'like', '%' . $searchTerm . '%')->get();

        if($tours->isEmpty()){
            return response()->json([
                'message' => 'Nothing Found!'
            ]);
        }
        return response()->json([
            'tours' => $tours
        ],200);

    }
}
