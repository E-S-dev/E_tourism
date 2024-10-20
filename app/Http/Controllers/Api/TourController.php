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
                'message' => 'الرحلة غير موجودة!'
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
            'number' => 'required|between:1,100',
            'description' => 'required|max:255|min:15',
        ],[
            //messages for Errores...............................

            'guide_id.required' => 'رجاء اختر مرشد',
            'driver_id.required' => 'رجاء اختر سائق',
            'programme_id.required' => 'رجاء اختر برنامج',
            'guide_id.exists' => 'المرشد غير موجود!',
            'driver_id.exists' => 'السائق غير موجود!',
            'programme_id.exists' => 'البرنامج غير موجود!',
            'photo.mimes' => 'يجب ان يكون نوع الصورة jpg او png',
            'photo.max' => 'يجب ان يكون الحجم الاقصى للصورة 2048px',
            'price.required' => 'حقل السعر مطلوب!',
            'startDate.date_format' => 'يجب ان يكون التاريخ بالصيغة yy-mm-dd.',
            'endDate.date_format' => 'يجب ان يكون التاريخ بالصيغة yy-mm-dd.',
            'number.required' => 'ادخل عدد السياح المطلوب!',
            'number.between' => 'يجب أن يكون الرقم بين 1 و 100 رقم.',
            'description.min' => 'يجب أن يكون الوصف 15 محرف كحد ادنى.',

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
            'message' => 'تم اضافة الرحلة بنجاح!',
            'tour' => $tour,
        ],201);
    }

//Update Tour Function------------------------------------------------------------------------

    function updateTour(Request $request, $id){

        $tour = Tour::find($id);

        if(!$tour){
            return response()->json([
                'message' => 'الرحلة غير موجودة!'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'guide_id' => 'required|exists:guides,id',
            'driver_id' => 'required|exists:drivers,id',
            'programme_id' => 'required|exists:programmes,id',
            'photo' => 'image|mimes:png,jpg|max:2048',
            'price' => 'required|numeric|between:0,9999.99',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
            'number' => 'required|between:1,100',
            'description' => 'required|min:15|max:255',
        ],[
            //messages for Errores...............................

            'guide_id.required' => 'رجاء اختر مرشد',
            'driver_id.required' => 'رجاء اختر سائق',
            'programme_id.required' => 'رجاء اختر برنامج',
            'guide_id.exists' => 'المرشد غير موجود!',
            'driver_id.exists' => 'السائق غير موجود!',
            'programme_id.exists' => 'البرنامج غير موجود!',
            'photo.mimes' => 'يجب ان يكون نوع الصورة jpg او png',
            'photo.max' => 'يجب ان يكون الحجم الاقصى للصورة 2048px',
            'price.required' => 'حقل السعر مطلوب!',
            'startDate.date_format' => 'يجب ان يكون التاريخ بالصيغة yy-mm-dd.',
            'endDate.date_format' => 'يجب ان يكون التاريخ بالصيغة yy-mm-dd.',
            'number.required' => 'ادخل عدد السياح المطلوب!',
            'number.between' => 'يجب أن يكون الرقم بين 1 و 100 رقم.',
            'description.min' => 'يجب أن يكون الوصف 15 محرف كحد ادنى.',

        ]);
        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
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
            'message' => 'تم تعديل الرحلة بنجاح.',
            'tour' => $tour,
        ]);

    }

//DELETE TOUR FUNCTION----------------------------------------------------------------------------

    function destroyTour(Request $request, $id){

        $tour = Tour::find($id);

        if(!$tour){return response()->json(['message' => 'الرحلة غير موجودة!']);}

        $tour->delete();

        return response()->json([
            'message' => 'تم حذف الرحلة بنجاح!'
        ]);
    }

    function searchTours(Request $request){

        $searchTerm = $request->keyword;
        if(!$searchTerm){
            return response()->json(['message' => 'الرجاء ادخال كلمة مفتاحية للبحث.'], 400);
        }

        $tours = Tour::where('description', 'like', '%' . $searchTerm . '%')->where('status', 'open')->get();

        if($tours->isEmpty()){
            return response()->json([
                'message' => 'لم يتم العثور على اية نتائج مطابقة للبحث!'
            ]);
        }
        return response()->json([
            'tours' => $tours
        ],200);

    }
}
