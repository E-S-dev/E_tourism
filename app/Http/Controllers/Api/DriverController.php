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
                'message' => 'Driver not found!'
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
            'plateNumber' => 'required|min:5|max:10',
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
            'message' => 'Driver added successfully.',
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
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $driver = Driver::find($id);

        if(!$driver){return response->json(['message' => 'Driver not found!']);}

        $driver->fName = $request->fName;
        $driver->lName = $request->lName;
        $driver->phoneNumber = $request->phoneNumber;
        $driver->plateNumber = $request->plateNumber;
        $driver->description = $request->description;

        $driver->save();

        return response()->json([
            'message' => 'Driver updated successfully.',
            'driver' => $driver,
        ], 201);

    }

//DELETE DRIVERS FUNCTION-------------------------------------------------------------------------------

    function destroyDriver(Request $request, $id){

        $driver = Driver::find($id);

        if(!$driver){return response()->json(['message' => 'Driver not found!']);}

        $driver->delete();

        return response()->json(['message' => 'Driver deleted successfully.']);
    }

    function getToursforDriver(Request $request){

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $drivers = Driver::withCount(['tours' => function($query) use ($startDate, $endDate){

            $query->whereBetween('startDate', [$startDate, $endDate])
                ->whereIn('status', ['open', 'closed']);

        }])->get()->makeHidden(['plateNumber','description','created_at', 'updated_at']);

        return response()->json([
            'drivers' => $drivers,

        ]);
    }
}
