<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Send forgot email.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPasswordEmail(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'=>'required|email|exists:users',
        ]);

        if ($validator->fails())
        {
            $message = $validator->errors()->first();
            return response()->json(['success' => false,'message' => $message], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );


        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 400);
    }
}
