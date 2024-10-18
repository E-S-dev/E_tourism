<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammeController extends Controller
{
    //GET ALL PROGRAMMES FUNCTION-------------------------------------------------------------------------

    function getProgrammes(Programme $programmes){

        return $programmes->all();
    }

    function getProgrammeDetails($id){
        $programme = Programme::find($id);
        if(!$programme){
            return response()->json([
                'message' => 'Programme not found!'
            ]);
        }
        return response()->json([
            'programme' => $programme
        ]);
    }

//ADD programmeS FUNCTION----------------------------------------------------------------------------------

    function addProgramme(Request $request){

        $validator = Validator::make($request ->all(),[
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
            'description' => 'required|min:15',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $programme = new Programme();
        $programme->type = $request->type;
        $programme->name = $request->name;
        $programme->description = $request->description;
        $programme->startDate = $request->startDate;
        $programme->endDate = $request->endDate;

        $programme->save();

        return response()->json([
            'message' => 'Programme added successfully.',
            'programme' => $programme,
        ], 201);

    }

//UPDATE programmeS FUNCTION-------------------------------------------------------------------------------

    function updateProgramme(Request $request, $id){

        $validator = Validator::make($request ->all(),[
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
             'description' => 'required|min:15',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $programme = Programme::find($id);

        if(!$programme){return response()->json(['message' => 'Programme not found!']);}

        $programme->type = $request->type;
        $programme->name = $request->name;
        $programme->description = $request->description;
        $programme->startDate = $request->startDate;
        $programme->endDate = $request->endDate;

        $programme->save();

        return response()->json([
            'message' => 'Programme updated successfully.',
            'programme' => $programme,
        ], 201);

    }

//DELETE programmeS FUNCTION-------------------------------------------------------------------------------

    function destroyProgramme(Request $request, $id){

        $programme = Programme::find($id);

        if(!$programme){return response()->json(['message' => 'Programme not found!']);}

        $programme->delete();

        return response()->json(['message' => 'Programme deleted successfully.']);
    }

    function getAvailableProgramme(Request $request){

        $date = $request->date;

        $programmes = Programme::where('startDate', $date)->whereHas('tours', function($query){
            $query->where('status','open');
        })->get();

        if($programmes->isEmpty()){
            return response()->json([
                'message' => 'No available programmes found for the specified date!'
            ]);
        }

        return response()->json([
            'programmes' => $programmes
        ]);

    }
}
