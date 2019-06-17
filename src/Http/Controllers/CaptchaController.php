<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
use App\User;

class CaptchaController extends Controller
{
    public function captchaForm()
    {
        return view('captchaform');
    }
    public function storeCaptchaForm(Request $request)
    {
        request()->validate([
            'g-recaptcha-response' => 'required|captcha',
        ]);

        dd('successfully validate');
    }
}