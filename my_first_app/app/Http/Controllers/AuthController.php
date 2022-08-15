<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
       //من أجل تسجيل دخول للمستخدم للمرة الأولى
    public function register(Request $request){

        $request->validate([
            'first_name' => ['required', 'string','min:3' ,'max:20'],
            'last_name' => ['required','string', 'max:20' ],
            'email' => ['required', 'email','max:200',],
            'password' => ['required','min:5'],
            'mobile_phone' => ['required','numeric','min:10' ],

        ]);


        $user = User::query()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_phone' => $request->mobile_phone,
        ]);

        $tokenResult = $user->createToken('personal access token');
        $data['user'] = $user;
        $data['typeToken'] = 'Bearer';
        $data['token'] = $tokenResult->accessToken;
        return response()->json(['message'=>'success,register is done',$data]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    //لتسجيل الدخول
    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email','max:200',],
            'password' => ['required','min:5'],
        ]);

        $emailPassword = $request->only(['email', 'password']);

        //اذا كانت محاولة تسجيل الدخول للتطبيق من قبل مستخدم لم يسبق له عمل ريجيستر
        if (!Auth::attempt($emailPassword)){
            throw new AuthenticationException();

        }

        $user = Auth::user();
        $tokenResult = $user->createToken('personal access token');//this is to create user token
        $data['user'] = $user; //store the info about the user that login to application
        $data['typeToken'] = 'Bearer'; //this is the type of the token
        $data['token'] = $tokenResult->accessToken;
        /*$token=$tokenResult->token
        if($request->remember_me)
        {$token->expires_at=Carbon::now()->addWeeks(1);}
        $token->save();*/
        return response()->json(['message'=>'success,login is done',$data]);

    }

    //لتسجيل الخروج
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message'=>'successfully logged out']);
    }
}
