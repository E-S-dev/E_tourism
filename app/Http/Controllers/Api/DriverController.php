<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{

//Get all Drivers Functions----------------------------------------------------------------------------

    function getDrivers(Driver $driver){

        return $driver->all();
    }

    function getDriverDetails($id){
        $driver = Driver::find($id);
        if(!$driver){
            return response()->json([
                'message' => 'السائق غير موجود'
            ]);
        }
        return response()->json([
            'driver' => $driver
        ]);
    }

//ADD DRIVERS FUNCTION----------------------------------------------------------------------------------

    function addDriver(Request $request){

        $validator = Validator::make($request ->all(),[
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'phoneNumber' => 'required|unique:drivers|min:10|max:15',
            'plateNumber' => 'required|unique:drivers|min:5|max:10',
        ],
            [
            'fName.required' => 'حقل الاسم الاول مطلوب',
            'lName.required' => 'حقل الاسم الاخير مطلوب',
            'phoneNumber.required' => 'رقم الهاتف مطلوب',
            'phoneNumber.unique' => 'رقم الهاتف موجود بالفعل موجود بالفعل!',
            'phoneNumber.min' => 'يجب أن يكون رقم الهاتف 10 ارقام كحد ادنى.',
            'phoneNumber.max' => 'يجب أن يكون رقم الهاتف 15 رقم كحد اقصى.',
            'plateNumber.required' => 'رقم السيارة مطلوب',
            'plateNumber.unique' => 'رقم السيارة موجود بالفعل موجود بالفعل!',
            'plateNumber.min' => 'يجب أن يكون رقم لوحة السيارة 5 ارقام كحد ادنى.',
            'plateNumber.max' => 'يجب أن يكون رقم لوحة السيارة 10 رقم كحد اقصى.',
            ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $driver = new Driver();
        $driver->fName = $request->fName;
        $driver->lName = $request->lName;
        $driver->phoneNumber = $request->phoneNumber;
        $driver->plateNumber = $request->plateNumber;
        $driver->description = $request->description;

        $driver->save();

        return response()->json([
            'message' => 'تم اضافة السائق بنجاح.',
            'driver' => $driver,
        ], 201);

    }

//UPDATE DRIVERS FUNCTION-------------------------------------------------------------------------------

    function updateDriver(Request $request, $id){

        $validator = Validator::make($request ->all(),[
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'phoneNumber' => 'required|min:10|max:15',
            'plateNumber' => 'required|min:5|max:10',
        ],
        [
        'fName.required' => 'حقل الاسم الاول مطلوب',
        'lName.required' => 'حقل الاسم الاخير مطلوب',
        'phoneNumber.required' => 'رقم الهاتف مطلوب',
        'phoneNumber.min' => 'يجب أن يكون رقم الهاتف 10 ارقام كحد ادنى.',
        'phoneNumber.max' => 'يجب أن يكون رقم الهاتف 15 رقم كحد اقصى.',
        'plateNumber.required' => 'رقم السيارة مطلوب',
        'plateNumber.min' => 'يجب أن يكون رقم لوحة السيارة 5 ارقام كحد ادنى.',
        'plateNumber.max' => 'يجب أن يكون رقم لوحة السيارة 10 رقم كحد اقصى.',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $driver = Driver::find($id);

        if(!$driver){return response->json(['message' => 'السائق غير موجود!']);}

        $driver->fName = $request->fName;
        $driver->lName = $request->lName;
        $driver->phoneNumber = $request->phoneNumber;
        $driver->plateNumber = $request->plateNumber;
        $driver->description = $request->description;

        $driver->save();

        return response()->json([
            'message' => 'تم تعديل معلومات السائق بنجاح!',
            'driver' => $driver,
        ], 201);

    }

//DELETE DRIVERS FUNCTION-------------------------------------------------------------------------------

    function destroyDriver(Request $request, $id){

        $driver = Driver::find($id);

        if(!$driver){return response()->json(['message' => 'السائق غير موجود!']);}

        $driver->delete();

        return response()->json(['message' => 'تم حذف السائق بنجاح!']);
    }

//GET TOURS FOR DRIVERS-----------------------------------------------------------------------------------

    function getToursforDriver(Request $request){

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $drivers = Driver::withCount(['tours' => function($query) use ($startDate, $endDate){

            $query->whereBetween('startDate', [$startDate, $endDate])
                ->whereIn('status', ['open', 'closed']);

        }])->get()->makeHidden(['description','created_at', 'updated_at']);

        return response()->json([
            'drivers' => $drivers,

        ]);
    }
}
