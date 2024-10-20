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

    function getGuideDetails($id){
        $guide = Guide::find($id);
        if(!$guide){
            return response()->json([
                'message' => 'المرشد غير موجود!'
            ]);
        }
        return response()->json([
            'guide' => $guide
        ]);
    }
//ADD GUIDES FUNCTION----------------------------------------------------------------------------------

    function addGuide(Request $request){

        $validator = Validator::make($request ->all(),[
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mobile' => 'required|unique:guides|min:10|max:15',
            'description' => 'required|min:15|max:255',
        ],
        [
        'fName.required' => 'حقل الاسم الاول مطلوب',
        'lName.required' => 'حقل الاسم الاخير مطلوب',
        'address.required' => 'حقل العنوان مطلوب',
        'mobile.required' => 'رقم الهاتف مطلوب',
        'mobile.unique' => 'رقم الهاتف موجود بالفعل موجود بالفعل!',
        'mobile.min' => 'يجب أن يكون رقم الهاتف 10 ارقام كحد ادنى.',
        'mobile.max' => 'يجب أن يكون رقم الهاتف 15 رقم كحد اقصى.',
        'description.required' => 'الوصف مطلوب',
        'description.min' => 'يجب أن يكون الوصف 15 رقم كحد ادنى.',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $guide = new Guide();
        $guide->fName = $request->fName;
        $guide->lName = $request->lName;
        $guide->address = $request->address;
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
            'address' => 'required|string|max:255',
            'mobile' => 'required|min:10|max:15',
            'description' => 'required|min:15|max:255',
        ],
        [
        'fName.required' => 'حقل الاسم الاول مطلوب',
        'lName.required' => 'حقل الاسم الاخير مطلوب',
        'address.required' => 'حقل العنوان مطلوب',
        'mobile.required' => 'رقم الهاتف مطلوب',
        'mobile.min' => 'يجب أن يكون رقم الهاتف 10 ارقام كحد ادنى.',
        'mobile.max' => 'يجب أن يكون رقم الهاتف 15 رقم كحد اقصى.',
        'description.required' => 'الوصف مطلوب',
        'description.min' => 'يجب أن يكون الوصف 15 رقم كحد ادنى.',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $guide = Guide::find($id);

        if(!$guide){return response->json(['message' => 'المرشد غير موجود!']);}

        $guide->fName = $request->fName;
        $guide->lName = $request->lName;
        $guide->address = $request->address;
        $guide->mobile = $request->mobile;
        $guide->description = $request->description;

        $guide->save();

        return response()->json([
            'message' => 'تم تعديل المرشد بنجاح!',
            'guide' => $guide,
        ], 201);

    }

//DELETE GUIDES FUNCTION-------------------------------------------------------------------------------

    function destroyGuide(Request $request, $id){

        $guide = Guide::find($id);

        if(!$guide){return response()->json(['message' => 'المرشد غير موجود!']);}

        $guide->delete();

        return response()->json(['message' => 'تم حذف المرشد بنجاح!']);
    }
}
