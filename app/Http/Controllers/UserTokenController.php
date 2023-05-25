<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class UserTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {   
        $request->validate([
            'email' => 'required|email',
            'password'  => 'required',
            'device_name'   => 'required',
        ]);

        $user = User::where('email', $request->get('email'))->first();


        if (!($user instanceof User)
            || !Hash::check($request->password, $user->password)
        ) {
            throw ValidationException::withMessages([
                'email' => 'El email no existe o no coincide con nuestros registros',
            ]);
            
        }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken,
        ]);
    }
}
