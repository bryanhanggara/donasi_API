<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            "email"=> ['required'],
            'password'=> ['required', 'min:8'],
        ]);


        if(Auth::attempt($credentials)) {
            return response()->json([
                'token' => Auth::user()->createToken('donasi')->plainTextToken,
                'status' => 200,
                'message' => 'berhasil login akun',
                'data' => $credentials,
                
            ]);
        }

        return response()->json([
            'message' => 'Gada akunnya'
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
