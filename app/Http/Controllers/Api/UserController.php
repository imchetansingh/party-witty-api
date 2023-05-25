<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->contact;
        $user->password = Hash::make($request->password);
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->referral_code = "PW".rand(00000000,99999999);
        $user->referral_by = $request->referral_by;
        $otp = rand(000000,999999);
        $user->otp = $otp;
        try {
            if($user->save()){
                $response = Http::withHeaders([
                    'Content-Type' => 'application/JSON'
                ])->post('https://api.msg91.com/api/v5/otp?template_id='.env('MSG91_TEMPLATE_ID').'&mobile=91' . $user->contact . '&authkey='.env('MSG91_AUTH_KEY'), [
                    'OTP' => $otp
                ]);
            }
            return $this->sendResponse('User registered successfully. Otp sendend on registered mobile number', $user, 201);
        } catch (\Exception $e) {
           return $this->sendError('User creation failed', $e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Verify the specified resource from storage.
     */
    public function verify(Request $request)
    {
        $user = User::where('contact', $request->contact)->first();
        if($user->otp == $request->otp){
            $user->status = 1;
            try{
                $user->save();
                // generate token
                Auth::guard('user')->login($user);
                $token = auth()->guard('user')->user()->createToken('user_login_token', ['admin:all'])->plainTextToken;
                $response = [
                    'token' => $token,
                    'user' => $user
                ];
                return $this->sendResponse('User verified successfully', $response, 200);
            }
            catch(\Exception $e){
                return $this->sendError('User verification failed', $e->getMessage(), 400);
            }

        }else{
            return $this->sendError('User verification failed', 'Invalid OTP', 400);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('contact', $request->contact)->first();
        if($user){
            $otp = rand(000000,999999);
            $user->otp = $otp;
            try {
                if($user->save()){
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/JSON'
                    ])->post('https://api.msg91.com/api/v5/otp?template_id='.env('MSG91_TEMPLATE_ID').'&mobile=91' . $user->contact . '&authkey='.env('MSG91_AUTH_KEY'), [
                        'OTP' => $otp
                    ]);
                }
                return $this->sendResponse('Otp sendend on registered mobile number', $user, 201);
            } catch (\Exception $e) {
               return $this->sendError('User creation failed', $e->getMessage(), 400);
            }
        }else{
            return $this->sendError('User not found', 'User not found', 400);
        }
    }
}
