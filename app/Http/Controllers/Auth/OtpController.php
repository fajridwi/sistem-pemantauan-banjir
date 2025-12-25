<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $otp = OtpVerification::where('code', $request->code)
            ->where('expired_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'message' => 'OTP tidak valid atau kadaluarsa'
            ], 422);
        }

        $otp->user->update([
            'email_verified_at' => now()
        ]);

        $otp->delete();

        return response()->json([
            'message' => 'Email berhasil diverifikasi'
        ]);
    }
}
