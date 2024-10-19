<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Tourist;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

//login function---------------------------------------------------------------------------------------

    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ],401);
        }

        $credentials = request(['email','password']);

            if(!$token = auth()->attempt($credentials)){
                return response()->json([
                    'message' => "Unauthorized user! please check your credentials."], 401);
            }

            $user = $request->user();
            // $token = $user->createToken(request()->userAgent())->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
                'role' => $user->getRoleNames()->first()
            ], 200);
    }


//Register Function------------------------------------------------------------------------------------

    public function register(Request $request){
        $validator = Validator::make($request ->all(),[
            'fName' => 'required|string|between:5,50',
            'lName' => 'required|string|between:5,50',
            'phoneNumber' => 'min:10|max:15',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }

        $tourist = Tourist::create($validator->validated());

        if($tourist){
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
            'tourist_id' => $tourist->id]
        ))->assignRole('user');
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registerd Successfully',
            'token' => $token,
            'user' => $user,
            'role' => $user->getRoleNames()->first()
        ]);

    }
//Register for Admin Function----------------------------------------------------------------------------

    public function adminRegister(Request $request){
        $validator = Validator::make($request ->all(),[
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 401);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ))->assignRole('admin');

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registerd Successfully',
            'token' => $token,
            'user' => $user,
            'role' => $user->getRoleNames()->first()
        ]);

    }

//LOGIUT FUNCTION-----------------------------------------------------------------------------------------

    function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out'], 200);
        }
        catch (\Exception $e){
            return response()->json(['error' => 'Faild to logout, please try again'], 500);
        }
    }
}
