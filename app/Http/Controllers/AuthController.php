<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verifyOtp', 'resendOtp']]);
    }


    // Register user
    public function register(Request $request)
    {
        try {
            $registerUserData = $request->validate([
                'name'          => 'required|string',
                'email'         => 'required|string|email|unique:users',
                'password'      => 'required|min:8',
                'c_password'    => 'required|same:password',
            ]);

            $otp = mt_rand(100000, 999999); // Generate 6-digit OTP

            // Store user data and OTP in cache
            $cacheKey = 'register_' . $registerUserData['email'];
            Cache::put($cacheKey, array_merge($registerUserData, ['otp' => $otp]), 600); // Cache for 10 minutes

            // Send OTP email
            Mail::to($registerUserData['email'])->send(new OtpMail($otp));

            return response()->json([
                'message' => 'User data received. Please check your email for OTP verification.',
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|numeric',
            ]);

            $cacheKey = 'register_' . $request->email;
            $registerUserData = Cache::get($cacheKey);

            if (!$registerUserData) {
                return response()->json([
                    'message' => 'No registration data found. Please register again.',
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($registerUserData['otp'] !== (int)$request->otp) {
                return response()->json([
                    'message' => 'Invalid OTP.',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Create the user
            $user = User::create([
                'name'      => $registerUserData['name'],
                'role_id'   => 2, // Static role_id
                'email'     => $registerUserData['email'],
                'password'  => Hash::make($registerUserData['password']),
                'email_verified_at' => Carbon::now('Asia/Phnom_Penh'),
            ]);

            // Clear cache data
            Cache::forget($cacheKey);

            // Generate token
            $token = Auth::guard('api')->login($user);

            return response()->json([
                'message' => 'Email verified successfully',
                'access_token' => $token,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
            ]);

            $cacheKey = 'register_' . $request->email;
            $registerUserData = Cache::get($cacheKey);

            if (!$registerUserData) {
                return response()->json([
                    'message' => 'No registration data found. Please register again.',
                ], Response::HTTP_BAD_REQUEST);
            }

            $otp = mt_rand(100000, 999999); // Generate new 6-digit OTP
            $registerUserData['otp'] = $otp;

            // Update cache with new OTP
            Cache::put($cacheKey, $registerUserData, 600); // Cache for another 10 minutes

            // Send OTP email
            Mail::to($request->email)->send(new OtpMail($otp));

            return response()->json([
                'message' => 'OTP has been resent to your email.',
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Login
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'     => 'required|string|email',
                'password'  => 'required|min:8'
            ]);
            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthorized',
                ], Response::HTTP_UNAUTHORIZED);
            }
            $user = Auth::guard('api')->user();
            return response()->json([
                'message'       => 'Success',
                'user'          => $user,
                'access_token'  => $token,
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            // Validation error
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            // Unexpected error
            return response()->json([
                'message' => 'Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update($request->only(['name', 'email', 'role_id']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user);
    }

    //delete user
    public function delete($id){
        try{
            $deleteUser = User::find($id);
            if (!$deleteUser){
                return response()->json(['message'=> 'User not found.'], 404);
            }
            $deleteUser->delete();
            
            return response()->json(['message'=> 'User deleted.'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function me()
    {
        // Here we just get information about current user
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            // Unexpected error
            return response()->json([
                'message' => 'Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function allUser(){
        $users = User::all();
        return response()->json($users, 200);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            //Unexpected error
            return response()->json([
                'message' => 'Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        // When access token will be expired, we are going to generate a new one wit this function 
        // and return it here in response
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        // This function is used to make JSON response with new
        // access token of current user
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 180
        ]);
    }
}