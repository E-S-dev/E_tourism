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
        ],[
            //messages for Errores...............................

            'email.required' => 'حقل الايميل مطلوب',
            'passowrd.required' => 'حقل كلمة المرور مطلوب',
            'email.email' => 'ادخل ايميل صالح!'

        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ],401);
        }

        $credentials = request(['email','password']);

            if(!$token = auth()->attempt($credentials)){
                return response()->json([
                    'message' => "المستخدم غير موجود الرجاء التحقق من المعلومات المدخلة!"], 401);
            }

            $user = $request->user();

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
            'phoneNumber' => 'required|unique:tourists|min:10|max:15',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ],[
            //messages for Errores...............................

            'fName.required' => 'حقل الاسم الاول مطلوب',
            'fName.between' => 'يجب أن يكون الاسم بين 5 و 50 حرفًا.',
            'lName.required' => 'حقل الاسم الاخير مطلوب',
            'lName.between' => 'يجب أن يكون الاسم بين 5 و 50 حرفًا.',
            'phoneNumber.required' => 'رقم الهاتف مطلوب',
            'phoneNumber.unique' => 'رقم الهاتف موجود بالفعل موجود بالفعل!',
            'phoneNumber.min' => 'يجب أن يكون رقم الهاتف 10 ارقام كحد ادنى.',
            'phoneNumber.max' => 'يجب أن يكون رقم الهاتف 15 رقم كحد اقصى.',
            'email.required' => 'حقل الايميل مطلوب',
            'email.email' => 'ادخل ايميل صالح!',
            'email.unique' => 'الايميل موجود بالفعل!',
            'passowrd.required' => 'حقل كلمة المرور مطلوب',
            'passowrd.confirmed' => 'يجب تاكيد كلمة المرور',
            'passowrd.min' => 'يجب ان تكون كلمة المرور اكثر من 6 محارف',
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
            'message' => 'تم التسجيل بنجاح',
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
        ],
        [
            'email.required' => 'حقل الايميل مطلوب',
            'email.email' => 'ادخل ايميل صالح!',
            'email.unique' => 'الايميل موجود بالفعل!',
            'passowrd.required' => 'حقل كلمة المرور مطلوب',
            'passowrd.confirmed' => 'يجب تاكيد كلمة المرور',
            'passowrd.min' => 'يجب ان تكون كلمة المرور اكثر من 6 محارف',
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
            'message' => 'تم التسجيل بنجاح',
            'token' => $token,
            'user' => $user,
            'role' => $user->getRoleNames()->first()
        ]);

    }

//LOGIUT FUNCTION-----------------------------------------------------------------------------------------

    function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'تم تسجيل الخروج بنجاح'], 200);
        }
        catch (\Exception $e){
            return response()->json(['error' => 'فشل في تسجيل الخروج, الرجاء المحاولة لاحقا!'], 500);
        }
    }
}
