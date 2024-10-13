<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuideController extends Controller
{

//Get All Guides Function--------------------------------------------------------------------------

    function getGuides(Guide $guide){

       return $guide->all();
    }

//ADD GUIDES FUNCTION----------------------------------------------------------------------------------

    function addGuide(Request $request){

        $validator = Validator::make($request ->all(),[
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'mobile' => 'required|min:10|max:15',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $guide = new Guide();
        $guide->fName = $request->fName;
        $guide->lName = $request->lName;
        $guide->mobile = $request->mobile;
        $guide->description = $request->description;

        $guide->save();

        return response()->json([
            'message' => 'Guide added successfully.',
            'guide' => $guide,
        ], 201);

    }

//UPDATE GUIDES FUNCTION-------------------------------------------------------------------------------

    function updateGuide(Request $request, $id){

        $validator = Validator::make($request ->all(),[
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'mobile' => 'required|min:10|max:15',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $guide = Guide::find($id);

        if(!$guide){return response->json(['message' => 'Guide not found!']);}

        $guide->fName = $request->fName;
        $guide->lName = $request->lName;
        $guide->mobile = $request->mobile;
        $guide->description = $request->description;

        $guide->save();

        return response()->json([
            'message' => 'Guide updated successfully.',
            'guide' => $guide,
        ], 201);

    }

//DELETE GUIDES FUNCTION-------------------------------------------------------------------------------

    function destroyGuide(Request $request, $id){

        $guide = Guide::find($id);

        if(!$guide){return response()->json(['message' => 'Guide not found!']);}

        $guide->delete();

        return response()->json(['message' => 'Guide deleted successfully.']);
    }
}
