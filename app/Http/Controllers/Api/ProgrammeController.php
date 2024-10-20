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
                'message' => 'البرنامج غير موجود!'
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
            'description' => 'required|min:15|max:255',
        ],
        [
        'type.required' => 'حقل النوع مطلوب',
        'name.required' => 'حقل الاسم مطلوب',
        'startDate.required' => 'حقل تاريخ البدء مطلوب',
        'endDate.required' => 'حقل تاريخ الانتهاء مطلوب',
        'startDate.date_format' => 'يكون ان يكون التاريخ بالصيغة yy-mm-dd.',
        'endDate.date_format' => 'يكون ان يكون التاريخ بالصيغة yy-mm-dd.',
        'description.required' => 'الوصف مطلوب',
        'description.min' => 'يجب أن يكون الوصف 15 رقم كحد ادنى.',
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
            'message' => 'تم اضافة البرنامج بنجاح',
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
            'description' => 'required|min:15|max:255',
        ],
        [
        'type.required' => 'حقل النوع مطلوب',
        'name.required' => 'حقل الاسم مطلوب',
        'startDate.required' => 'حقل تاريخ البدء مطلوب',
        'endDate.required' => 'حقل تاريخ الانتهاء مطلوب',
        'startDate.date_format' => 'يجب ان يكون التاريخ بالصيغة yy-mm-dd.',
        'endDate.date_format' => 'يجب ان يكون التاريخ بالصيغة yy-mm-dd.',
        'description.required' => 'الوصف مطلوب',
        'description.min' => 'يجب أن يكون الوصف 15 محرف كحد ادنى.',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $programme = Programme::find($id);

        if(!$programme){return response()->json(['message' => 'البرنامج غير موجود!']);}

        $programme->type = $request->type;
        $programme->name = $request->name;
        $programme->description = $request->description;
        $programme->startDate = $request->startDate;
        $programme->endDate = $request->endDate;

        $programme->save();

        return response()->json([
            'message' => 'تم تعديل البرنامج بنجاح!',
            'programme' => $programme,
        ], 201);

    }

//DELETE programmeS FUNCTION-------------------------------------------------------------------------------

    function destroyProgramme(Request $request, $id){

        $programme = Programme::find($id);

        if(!$programme){return response()->json(['message' => 'البرنامج غير موجود']);}

        $programme->delete();

        return response()->json(['message' => 'تم حذف البرنامج بنجاح.']);
    }

//GET AVAILABLE PROGRAMMES----------------------------------------------------------------------------------

    function getAvailableProgramme(Request $request){

        $date = $request->date;

        $programmes = Programme::where('startDate','<=', $date)->where('endDate','>=', $date)->whereHas('tours', function($query){
            $query->where('status','open');})

        ->with(['tours' => function($query){
            $query->where('status','open');}])

        ->withCount(['tours' => function($query){
            $query->where('status','open');
        }])->get();

        if($programmes->isEmpty()){
            return response()->json([
                'message' => 'لا توجد برامج متاحة خلال الفترة الزمنية المحددة'
            ]);
        }

        return response()->json([
            'programmes' => $programmes
        ]);

    }
}
