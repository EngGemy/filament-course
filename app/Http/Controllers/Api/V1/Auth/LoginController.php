<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //validte request
        $request->validate([
            "email"=>['required' ,'email',],
            "password"=>['required'],
        ]);


        //query find first record matching Email

        $user = User::where('email',$request->email)->first();

        if(! $user ||  ! Hash::check($request->password,$user->password) ){
            Throw ValidationException::withMessages([
                'email' => ["cardetionakls not corerwect"]
            ]);
        }

        $device =  substr($request->userAgent() ?? " " , 0 ,255);

        return response()->json([
            'token' => $user->createToken($device)->plainTextToken,
        ] ,Response::HTTP_CREATED);
    }
}
